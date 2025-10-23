<?php

namespace App\Services;

use App\Jobs\GalleryImageDownloadJob;
use App\Jobs\ProcessProductImages;
use App\Models\Attribute;
use App\Models\ExpenseCategory;
use App\Models\Product;
use App\Models\PriceType;
use App\Models\ProductPrice;
use App\Models\Category;
use App\Models\MediaConversions;
use App\Http\Enums\ProductAttributeEnum;
use Spatie\Image\Enums\Fit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\Products\Product as ApiProduct;
use Illuminate\Support\Facades\File;
use MoySklad\MoySklad;

class ProductService
{
    /**
     * @throws \Exception
     */
    public function processProducts($offset = 0, $limit = 10): void
    {
        $myStore = MoySklad::getInstance(config('app.my_store.username'), config('app.my_store.password'));

        $list = ApiProduct::query($myStore, QuerySpecs::create([
            'offset'     => $offset,
            'maxResults' => $limit,
        ]))->getList();

//        $jsonUrl = "https://api.moysklad.ru/api/remap/1.2/entity/currency/76e1fb94-76b8-11eb-0a80-00ab004bdad2";

        $username = config('app.my_store.username');
        $password = config('app.my_store.password');
        $encodedCredentials = base64_encode("{$username}:{$password}");

//        $response = Http::withHeaders([
//            'Authorization'   => 'Basic ' . $encodedCredentials,
//            'Accept-Encoding' => 'gzip',
//        ])->get($jsonUrl);

//        if ($response->successful()) {
//            $data = $response->json()['rate'];
//            File::put(storage_path('app/currency_rate.json'), json_encode(['rate' => $data]));
//         }

        foreach ($list as $item) {
            try {
                DB::beginTransaction();

                $this->processProduct($item);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e->getMessage(),$e->getTrace());
                dd($e->getLine() ,$e->getMessage(),$e->getTraceAsString());
                Log::error('Ошибка при обработке продукта: ' . $e->getMessage());
            }
        }

        $this->processImages();
    }

    /**
     * @throws \Exception
     */
    public function processImages(): void
    {
        $products = Product::all()->filter(function (Product $product) {
            return $product->whereNotNull('external_id');
        });

        $products->each(function ($product) {
            ProcessProductImages::dispatch($product);
        });
    }

    protected function syncImages(Collection $products)
    {
        $username = config('app.my_store.username');
        $password = config('app.my_store.password');
        $encodedCredentials = base64_encode("{$username}:{$password}");

        foreach ($products as $product) {
            $prodExternalId = $product->external_id;
            $jsonUrl = "https://api.moysklad.ru/api/remap/1.2/entity/product/{$prodExternalId}/images";

            $response = Http::withHeaders([
                'Authorization'   => 'Basic ' . $encodedCredentials,
                'Accept-Encoding' => 'gzip',
            ])->get($jsonUrl);

            if ($response->successful()) {
                $data = $response->json();


                if (isset($data['rows']) && is_array($data['rows'])) {
                    foreach ($data['rows'] as $image) {
                        $downloadUrl = $image['meta']['downloadHref'] ?? null;
                        if ($downloadUrl && filter_var($downloadUrl, FILTER_VALIDATE_URL)) {
                            $this->handleImageDownload($product, $downloadUrl, $encodedCredentials);
                        } else {
                            Log::warning("Invalid download URL for product ID {$prodExternalId}");
                        }
                    }
                } else {
                    Log::warning("No images found for product ID {$prodExternalId}.");
                }
            } else {
                Log::error("Failed to get images for product ID {$prodExternalId}. Status: {$response->status()}");
            }
        }

        return response()->json([
            'message' => 'Image synchronization completed.',
        ]);
    }

    protected function handleImageUpdate($product, $downloadUrl, $encodedCredentials, $imageIndex = 0): void
    {
        $imageResponse = Http::withHeaders([
            'Authorization'   => 'Basic ' . $encodedCredentials,
            'Accept-Encoding' => 'gzip',
        ])->get($downloadUrl);

        if ($imageResponse->successful()) {
            $fileContent = $imageResponse->body();
            $hash = md5($fileContent);
            $uniqueFilename = $hash . '.png';

            try {
                // Получаем все существующие изображения товара
                $existingImages = $product->getMedia('images');

                if ($imageIndex < $existingImages->count()) {
                    // Обновляем существующее изображение по индексу: пересоздаем медиа с сохранением порядка и пропертей
                    $existingMedia = $existingImages[$imageIndex];
                    $oldOrder = property_exists($existingMedia, 'order_column') && $existingMedia->order_column !== null
                        ? $existingMedia->order_column
                        : $imageIndex;

                    // Сначала регистрируем конверсии в модели продукта
                    $product->registerMediaConversions();

                    // Создаём новое медиа из потока
                    $newMedia = $product->addMediaFromStream($fileContent)
                        ->usingFileName($uniqueFilename)
                        ->toMediaCollection('images');

                    // Копируем order и custom props
                    $newMedia->order_column = $oldOrder;
                    $newMedia->setCustomProperty('moysklad_hash', $hash);
                    // Сливаем существующие кастомные пропсы, если есть
                    if (is_array($existingMedia->custom_properties)) {
                        foreach ($existingMedia->custom_properties as $k => $v) {
                            if ($k !== 'moysklad_hash') {
                                $newMedia->setCustomProperty($k, $v);
                            }
                        }
                    }
                    $newMedia->save();

                    // Удаляем старое медиа
                    $existingMediaId = $existingMedia->id;
                    $existingMedia->delete();

                    // Принудительно генерируем конверсии для конкретного медиа
                    $this->generateConversionsForMedia($product, $newMedia);

                    Log::info('Изображение пересоздано (обновлено)', [
                        'product_id' => $product->id,
                        'hash' => $hash,
                        'filename' => $uniqueFilename,
                        'old_media_id' => $existingMediaId,
                        'new_media_id' => $newMedia->id,
                        'order' => $oldOrder,
                        'image_index' => $imageIndex,
                        'generated_conversions' => $newMedia->fresh()->getGeneratedConversions()->keys()->toArray()
                    ]);
                } else {
                    // Если изображений меньше чем индекс, создаем новое
                    // Сначала регистрируем конверсии в модели продукта
                    $product->registerMediaConversions();
                    
                    $mediaItem = $product->addMediaFromStream($fileContent)
                        ->usingFileName($uniqueFilename)
                        ->toMediaCollection('images');

                    $mediaItem->setCustomProperty('moysklad_hash', $hash);
                    $mediaItem->save();

                    // Принудительно генерируем конверсии для конкретного медиа
                    $this->generateConversionsForMedia($product, $mediaItem);

                    Log::info('Изображение создано', [
                        'product_id' => $product->id,
                        'hash' => $hash,
                        'filename' => $uniqueFilename,
                        'media_id' => $mediaItem->id,
                        'image_index' => $imageIndex,
                        'generated_conversions' => $mediaItem->fresh()->getGeneratedConversions()->keys()->toArray()
                    ]);
                }

            } catch (\Exception $e) {
                Log::error("Failed to update image for product ID {$product->external_id}. Error: {$e->getMessage()}");
            }
        } else {
            Log::error("Failed to download image from {$downloadUrl}. Status: {$imageResponse->status()}");
        }
    }

    /**
     * Публичный метод для синхронизации товара из вебхука
     */
    public function syncProductFromWebhook(array $productData): void
    {
        try {
            Log::info('Начало синхронизации товара из вебхука', [
                'product_id' => $productData['id'] ?? 'unknown',
                'product_name' => $productData['name'] ?? 'unknown',
                'product_code' => $productData['code'] ?? 'unknown'
            ]);

            DB::beginTransaction();

            // Для вебхуков синхронизируем товар напрямую без проверки атрибута "Сайт"
            $this->processProductFromWebhookData($productData);

            DB::commit();
            Log::info('Товар успешно синхронизирован из вебхука', [
                'product_id' => $productData['id'] ?? 'unknown'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка синхронизации товара из вебхука: ' . $e->getMessage(), [
                'product_id' => $productData['id'] ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Обработка товара из вебхука с проверкой атрибута "Сайт"
     */
    private function processProductFromWebhookData(array $productData): void
    {
        Log::info('Обработка товара из вебхука с проверкой атрибута', [
            'product_id' => $productData['id'] ?? 'unknown',
            'product_name' => $productData['name'] ?? 'unknown',
            'product_code' => $productData['code'] ?? 'unknown'
        ]);

        // Проверяем атрибут "Сайт" как в оригинальном коде
        if (isset($productData['attributes'])) {
            $hasSiteAttribute = false;
            $siteAttributeValue = null;

            foreach ($productData['attributes'] as $attribute) {
                if (isset($attribute['id']) && $attribute['id'] == ProductAttributeEnum::SITE) {
                    $siteAttributeValue = $attribute['value']['name'] ?? $attribute['value'] ?? null;
                    if ($siteAttributeValue === 'так') {
                        $hasSiteAttribute = true;
                        break;
                    }
                }
            }

            Log::info('Проверка атрибута "Сайт"', [
                'product_id' => $productData['id'] ?? 'unknown',
                'has_site_attribute' => $hasSiteAttribute,
                'site_attribute_value' => $siteAttributeValue,
                'total_attributes' => count($productData['attributes'])
            ]);

            // Проверяем, существует ли товар
            $existingProduct = \App\Models\Product::where('external_id', $productData['id'])->first();

            if (!$hasSiteAttribute) {
                // Если товар есть, но атрибут "Сайт" = "ні" - деактивируем
                if ($existingProduct) {
                    $existingProduct->update(['is_active' => false]);
                    Log::info('Товар деактивирован - атрибут "Сайт" = "ні"', [
                        'product_id' => $existingProduct->id,
                        'external_id' => $productData['id']
                    ]);
                } else {
                    Log::info('Товар не имеет атрибута "Сайт" со значением "так" - пропускаем', [
                        'product_id' => $productData['id'] ?? 'unknown',
                        'site_value' => $siteAttributeValue
                    ]);
                }
                return;
            } else {
                // Если товар был деактивирован, но теперь атрибут "Сайт" = "так" - активируем
                if ($existingProduct && !$existingProduct->is_active) {
                    $existingProduct->update(['is_active' => true]);
                    Log::info('Товар активирован - атрибут "Сайт" = "так"', [
                        'product_id' => $existingProduct->id,
                        'external_id' => $productData['id']
                    ]);
                }
            }
        } else {
            Log::warning('Товар не имеет атрибутов - пропускаем', [
                'product_id' => $productData['id'] ?? 'unknown'
            ]);
            return;
        }

        // $existingProduct уже определен выше
        $isNewProduct = !$existingProduct;

        if ($isNewProduct) {
            // Новый продукт - создаем все 3 локализации
            $product = \App\Models\Product::create([
                'external_id' => $productData['id'],
                'external_code' => $productData['externalCode'] ?? null,
                'code'          => $productData['code'] ?? null,
                'article'       => $productData['article'] ?? null,
                'name'          => [
                    'ru' => $productData['name'] ?? '',
                    'uk' => $productData['name'] ?? '',
                    'en' => $productData['name'] ?? '',
                ],
                'descriptions'   => [
                    'ru' => $productData['description'] ?? '',
                    'uk' => $productData['description'] ?? '',
                    'en' => $productData['description'] ?? '',
                ],
            ]);

            Log::info("Создан новый продукт из вебхука", [
                'external_id' => $productData['id'],
                'code' => $productData['code'] ?? null
            ]);
        } else {
            // Старый продукт - обновляем только UK значения переводов
            $product = $existingProduct;
            $product->update([
                'external_code' => $productData['externalCode'] ?? $product->external_code,
                'code'          => $productData['code'] ?? $product->code,
                'article'       => $productData['article'] ?? $product->article,
                'name' => [
                    'uk' => $productData['name'] ?? $product->getTranslation('name', 'uk'),
                    // ru и en остаются как есть
                ],
                'descriptions' => [
                    'uk' => $productData['description'] ?? $product->getTranslation('descriptions', 'uk'),
                    // ru и en остаются как есть
                ],
            ]);

            Log::info("Обновлен существующий продукт из вебхука (только UK переводы)", [
                'external_id' => $productData['id'],
                'code' => $productData['code'] ?? null
            ]);
        }

        // Обновляем слаги
        if ($isNewProduct) {
            // Для новых товаров - создаем все слаги
            $enName = $product->getTranslation('name', 'en') ?: $product->name ?: 'product';
            $ukName = $product->getTranslation('name', 'uk') ?: $product->name ?: 'product';
            $ruName = $product->getTranslation('name', 'ru') ?: $product->name ?: 'product';

            $product->slug = [
                'en' => \Illuminate\Support\Str::slug($enName),
                'uk' => \Illuminate\Support\Str::slug($ukName),
                'ru' => \Illuminate\Support\Str::slug($ruName)
            ];
        } else {
            // Для существующих товаров - обновляем только UK слаг
            $currentSlug = $product->slug ?? [];

            Log::info('Обработка слага для существующего товара', [
                'product_id' => $product->id,
                'current_slug_type' => gettype($currentSlug),
                'current_slug_value' => $currentSlug
            ]);

            // Проверяем, что slug является массивом
            if (!is_array($currentSlug)) {
                // Если slug - строка, создаем массив с этой строкой как UK слагом
                $currentSlug = ['uk' => $currentSlug];
                Log::info('Преобразован строковый слаг в массив', [
                    'product_id' => $product->id,
                    'converted_slug' => $currentSlug
                ]);
            }

            // Получаем название товара на украинском
            $ukName = $product->getTranslation('name', 'uk');
            if (empty($ukName)) {
                $ukName = $product->name ?? 'product';
                Log::warning('Название товара на украинском пустое, используем общее название', [
                    'product_id' => $product->id,
                    'fallback_name' => $ukName
                ]);
            }

            $newSlug = array_merge($currentSlug, [
                'uk' => \Illuminate\Support\Str::slug($ukName)
            ]);

            Log::info('Обновлен слаг товара', [
                'product_id' => $product->id,
                'new_slug' => $newSlug
            ]);

            $product->slug = $newSlug;
        }
        $product->save();

        // Обрабатываем цены
        if (isset($productData['salePrices'])) {
            $this->processPrices($productData['salePrices'], $product);
        }

        // Обрабатываем атрибуты товара
        if (isset($productData['attributes'])) {
            $this->processAttributesFromWebhook($productData['attributes'], $product);
        }

        // Обрабатываем категории товара
        if (isset($productData['attributes'])) {
            $this->processCategoriesFromWebhook($productData['attributes'], $product);
        }

        // Обрабатываем изображения товара
        if (isset($productData['images']['meta']['href'])) {
            $this->processImagesFromWebhook($productData['images']['meta']['href'], $product);
        }

        Log::info('Товар обработан из вебхука', [
            'product_id' => $product->id,
            'external_id' => $product->external_id
        ]);
    }

    /**
     * Обработка атрибутов товара из вебхука
     */
    private function processAttributesFromWebhook(array $attributes, Product $product): void
    {
        Log::info('Обработка атрибутов товара из вебхука', [
            'product_id' => $product->id,
            'attributes_count' => count($attributes)
        ]);

        foreach ($attributes as $attribute) {
            try {
                $this->processAttributeFromWebhook($attribute, $product);
            } catch (\Exception $e) {
                Log::error('Ошибка обработки атрибута из вебхука', [
                    'product_id' => $product->id,
                    'attribute_id' => $attribute['id'] ?? 'unknown',
                    'attribute_name' => $attribute['name'] ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Обработка одного атрибута из вебхука
     */
    private function processAttributeFromWebhook(array $attribute, Product $product): void
    {
        $attributeId = $attribute['id'] ?? null;
        $attributeName = $attribute['name'] ?? 'unknown';
        $attributeValue = $attribute['value'] ?? null;

        if (!$attributeId) {
            return;
        }

        // Создаем или обновляем атрибут
        $productAttribute = Attribute::updateOrCreate(
            ['external_id' => $attributeId],
            [
                'field_name' => $this->getFieldNameByAttributeId($attributeId),
                'name' => [
                    'uk' => $attributeName,
                    'ru' => $attributeName,
                    'en' => $attributeName,
                ],
            ]
        );

        // Определяем значение атрибута
        $value = null;
        if (is_array($attributeValue) && isset($attributeValue['name'])) {
            $value = $attributeValue['name'];
        } elseif (is_string($attributeValue)) {
            $value = $attributeValue;
        }

        if ($value !== null) {
            // Обновляем или создаем связь с товаром
            $existingPivot = $product->attributes()->where('attribute_id', $productAttribute->id)->first();

            $attributeData = [
                'value' => [
                    'uk' => $value,
                    'ru' => $value,
                    'en' => $value,
                ]
            ];

            if ($existingPivot) {
                $product->attributes()->updateExistingPivot($productAttribute->id, $attributeData);
            } else {
                $product->attributes()->attach($productAttribute->id, $attributeData);
            }

            Log::info('Атрибут обработан из вебхука', [
                'product_id' => $product->id,
                'attribute_id' => $attributeId,
                'attribute_name' => $attributeName,
                'value' => $value
            ]);
        }
    }

    /**
     * Получение имени поля по ID атрибута
     */
    private function getFieldNameByAttributeId(string $attributeId): string
    {
        $fieldMap = [
            ProductAttributeEnum::SITE => 'site',
            ProductAttributeEnum::SITE_CATEGORY => 'site_category',
            ProductAttributeEnum::NAME => 'name',
            ProductAttributeEnum::BRAND => 'brand',
            ProductAttributeEnum::TYPE => 'type',
            ProductAttributeEnum::MATERIAL => 'material',
            ProductAttributeEnum::THICKNESS => 'thickness',
            ProductAttributeEnum::WIDTH_M => 'width',
            ProductAttributeEnum::APPLICATION => 'application',
            ProductAttributeEnum::BENEFITS => 'benefits',
            ProductAttributeEnum::VOLUME => 'volume',
            ProductAttributeEnum::COUNTRY_OF_MANUFACTURER => 'country_manufacture',
            ProductAttributeEnum::DEFAULT_QUANTITY => 'default_quantity',
            ProductAttributeEnum::QUANTITY_STEP => 'quantity_step',
            ProductAttributeEnum::MINIMUM_ORDER_QUANTITY => 'min_order_quantity',
            ProductAttributeEnum::UNDER_ORDER => 'under_order',
        ];

        return $fieldMap[$attributeId] ?? 'custom_' . $attributeId;
    }

    /**
     * Обработка категорий товара из вебхука
     */
    private function processCategoriesFromWebhook(array $attributes, Product $product): void
    {
        foreach ($attributes as $attribute) {
            if (isset($attribute['id']) && $attribute['id'] == ProductAttributeEnum::SITE_CATEGORY) {
                $categoryPath = $attribute['value']['name'] ?? $attribute['value'] ?? null;

                if ($categoryPath) {
                    try {
                        $categoryIds = $this->getCategoryIds(trim($categoryPath));
                        $product->update(['category_id' => end($categoryIds)]);

                        Log::info('Категория товара обновлена из вебхука', [
                            'product_id' => $product->id,
                            'category_path' => $categoryPath,
                            'category_id' => end($categoryIds)
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Ошибка обработки категории из вебхука', [
                            'product_id' => $product->id,
                            'category_path' => $categoryPath,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                break;
            }
        }
    }

    /**
     * Обработка изображений товара из вебхука
     */
    private function processImagesFromWebhook(string $imagesUrl, Product $product): void
    {
        try {
            Log::info('Обработка изображений товара из вебхука', [
                'product_id' => $product->id,
                'images_url' => $imagesUrl
            ]);

            $response = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
            ->withHeaders([
                'Accept-Encoding' => 'gzip',
            ])
            ->get($imagesUrl);

            if ($response->successful()) {
                $imagesData = $response->json();

                if (isset($imagesData['rows']) && is_array($imagesData['rows'])) {
                // Получаем существующие изображения товара
                $existingImages = $product->getMedia('images');
                $existingHashes = $existingImages->map(function ($media) {
                    return $media->getCustomProperty('moysklad_hash');
                })->filter()->toArray();

                // Получаем хеши изображений из МойСклад
                $moyskladHashes = [];
                foreach ($imagesData['rows'] as $image) {
                    $downloadUrl = $image['meta']['downloadHref'] ?? null;
                    if ($downloadUrl && filter_var($downloadUrl, FILTER_VALIDATE_URL)) {
                        // Получаем хеш изображения из МойСклад
                        $response = Http::withBasicAuth(
                            config('app.my_store.username'),
                            config('app.my_store.password')
                        )
                        ->withHeaders([
                            'Accept-Encoding' => 'gzip',
                        ])
                        ->get($downloadUrl);

                        if ($response->successful()) {
                            $fileContent = $response->body();
                            $hash = md5($fileContent);
                            $moyskladHashes[] = $hash;
                        }
                    }
                }

                // Удаляем изображения, которых нет в МойСклад
                $deletedImagesCount = 0;
                foreach ($existingImages as $media) {
                    $mediaHash = $media->getCustomProperty('moysklad_hash');
                    if ($mediaHash && !in_array($mediaHash, $moyskladHashes)) {
                        $media->delete();
                        $deletedImagesCount++;
                        Log::info('Изображение удалено (отсутствует в МойСклад)', [
                            'product_id' => $product->id,
                            'media_id' => $media->id,
                            'hash' => $mediaHash
                        ]);
                    }
                }

                $updatedImagesCount = 0;
                $skippedImagesCount = 0;
                $imageIndex = 0;

                foreach ($imagesData['rows'] as $image) {
                    $downloadUrl = $image['meta']['downloadHref'] ?? null;
                    if ($downloadUrl && filter_var($downloadUrl, FILTER_VALIDATE_URL)) {
                        // Проверяем, нужно ли обновлять изображение
                        if ($this->shouldUpdateImage($product, $downloadUrl, $existingHashes)) {
                            $this->handleImageUpdate($product, $downloadUrl, base64_encode(config('app.my_store.username') . ':' . config('app.my_store.password')), $imageIndex);
                            $updatedImagesCount++;
                        } else {
                            $skippedImagesCount++;
                        }
                        $imageIndex++;
                    }
                }

                    Log::info('Изображения товара обработаны из вебхука', [
                        'product_id' => $product->id,
                        'total_images' => count($imagesData['rows']),
                        'updated_images' => $updatedImagesCount,
                        'skipped_images' => $skippedImagesCount,
                        'deleted_images' => $deletedImagesCount,
                        'existing_images' => $existingImages->count()
                    ]);
                }
            } else {
                Log::error('Ошибка получения изображений из вебхука', [
                    'product_id' => $product->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Исключение при обработке изображений из вебхука', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Проверка, нужно ли обновлять изображение
     */
    private function shouldUpdateImage(Product $product, string $downloadUrl, array $existingHashes): bool
    {
        try {
            // Получаем содержимое изображения для проверки хеша
            $response = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
            ->withHeaders([
                'Accept-Encoding' => 'gzip',
            ])
            ->get($downloadUrl);

            if ($response->successful()) {
                $fileContent = $response->body();
                $hash = md5($fileContent);

                // Проверяем, есть ли уже изображение с таким хешем у этого товара
                if (in_array($hash, $existingHashes)) {
                    Log::info('Изображение не изменилось, пропускаем', [
                        'product_id' => $product->id,
                        'hash' => $hash,
                        'download_url' => $downloadUrl
                    ]);
                    return false;
                }

                Log::info('Изображение изменилось, обновляем', [
                    'product_id' => $product->id,
                    'hash' => $hash,
                    'download_url' => $downloadUrl
                ]);
                return true;
            }
        } catch (\Exception $e) {
            Log::warning('Ошибка проверки изображения, скачиваем', [
                'product_id' => $product->id,
                'download_url' => $downloadUrl,
                'error' => $e->getMessage()
            ]);
        }

        return true; // В случае ошибки скачиваем изображение
    }

    /**
     * @param $item
     */
    protected function processProduct($item): void
    {
        $parseData = $item->jsonSerialize();

       if (property_exists($parseData, 'attributes')) {
           $attributes = $parseData?->attributes?->attrs;

           $product = null;

           foreach ($attributes as $attribute) {
               if ($attribute->id === ProductAttributeEnum::SITE) {
                   if ($attribute->value->name === 'так'){
                       // Проверяем, существует ли продукт
                       $existingProduct = Product::where('external_id', $parseData->id)->first();
                       $isNewProduct = !$existingProduct;

                       if ($isNewProduct) {
                           $product = Product::create([
                               'external_id' => $parseData->id,
                               'external_code' => $parseData->externalCode,
                               'code'          => $parseData->code,
                               'article'       => $parseData->article ?? null,
                               'name'          => [
                                   'ru' => $parseData->name ?? '',
                                   'uk' => $parseData->name ?? '',
                                   'en' => $parseData->name ?? '',
                               ],
                               'descriptions'   => [
                                   'ru' => $parseData->description ?? '',
                                   'uk' => $parseData->description ?? '',
                                   'en' => $parseData->description ?? '',
                               ],
                           ]);

                           Log::info("Создан новый продукт", [
                               'external_id' => $parseData->id,
                               'code' => $parseData->code
                           ]);
                       } else {
                           // Старый продукт - обновляем только UK локализацию
                           $product = $existingProduct;
                           $product->update([
                               'external_code' => $parseData->externalCode,
                               'code'          => $parseData->code,
                               'article'       => $parseData->article ?? null,
                               'name'          => [
                                   'uk' => $parseData->name ?? $product->getTranslation('name', 'uk'),
                               ],
                               'descriptions'   => [
                                   'uk' => $parseData->description ?? $product->getTranslation('descriptions', 'uk'),
                               ],
                           ]);

                           Log::info("Обновлен существующий продукт (только UK)", [
                               'external_id' => $parseData->id,
                               'code' => $parseData->code
                           ]);
                       }

                       $product->slug = [
                           'en' => Str::slug($product->getTranslation('name', 'en')),
                           'uk' => Str::slug($product->getTranslation('name', 'uk')),
                           'ru' => Str::slug($product->getTranslation('name', 'ru'))
                       ];

                       $product->save();

                       $this->processPrices($parseData->salePrices, $product);
                   }
                   else{
                       return;
                   }
               }
           }

           if (!$product){
               return;
           }

           $this->processCategories($attributes, $product);
           $this->updateName($attributes, $product);
           $this->processBrand($attributes, $product);
           $this->processRollSize($attributes, $product);
           $this->processFirstStock($attributes, $product);
           $this->processSecondStock($attributes, $product);
           $this->processThirdStock($attributes, $product);
           $this->processThickness($attributes, $product);
           $this->processMaterialStretchingPercent($attributes, $product);
           $this->processProtectionLiner($attributes, $product);
           $this->processMasterQualification($attributes, $product);
           $this->processAdhesion($attributes, $product);
           $this->processStoreTerms($attributes, $product);
           $this->processServiceLife($attributes, $product);
           $this->processWarranty($attributes, $product);
           $this->processProductTechnology($attributes, $product);
           $this->processQuantityStep($attributes, $product);
           $this->processMinOrderQuantity($attributes, $product);
           $this->processApplication($attributes, $product);
           $this->processRoomTemperature($attributes, $product);
           $this->processPropose($attributes, $product);
           $this->updateBenefits($attributes, $product);
           $this->updateMaterial($attributes, $product);
           $this->updateStructure($attributes, $product);
           $this->updateOperatingTemperature($attributes, $product);
           $this->updateWidth($attributes, $product);
           $this->updateApplicationMethod($attributes, $product);
           $this->updateSurfaceTemperature($attributes, $product);
           $this->processMainShade($attributes, $product);
           $this->processType($attributes, $product);
           $this->processFormRelease($attributes, $product);
           $this->processVolume($attributes, $product);
           $this->processCountryManufacture($attributes, $product);
           $this->processDefaultQuantity($attributes, $product);
           $this->processUnderOrder($attributes, $product);
           $this->saveProductGalleryLinks($attributes, $product);
       }
    }

    protected function saveProductGalleryLinks($attributes, Product $product): void
    {
        $hasGallery = false;
        foreach ($attributes as $attribute) {
            if ($attribute->type === 'link') {
                $this->saveGalleryLink($attribute, $product);
                $hasGallery = true;
            }

            if ($hasGallery){
                $product->update(['banner_title' => [
                    'en' => 'Look at how this film will look on the car',
                    'uk' => 'Подивіться, як виглядатиме ця плівка на автомобілі',
                    'ru' => 'Посмотрите, как будет выглядеть эта пленка на автомобиле'
                ]]);
            }
        }
    }

    protected function saveGalleryLink($attribute, $product): void
    {
        if (filter_var($attribute->value, FILTER_VALIDATE_URL)) {
            GalleryImageDownloadJob::dispatch($attribute->value, $product);
        }
    }

    /**
     * @param $salePrices
     * @param $product
     */
    protected function processPrices($salePrices, $product): void
    {
        // Обрабатываем цены из вебхука (массив) или из API (объекты)
        if (is_array($salePrices) && isset($salePrices[0]) && is_array($salePrices[0])) {
            // Цены из вебхука
            $priceTypes = array_map(function ($salePrice) use ($product) {
                return [
                    'external_id'   => $salePrice['priceType']['id'] ?? null,
                    'name'          => $salePrice['priceType']['name'] ?? 'Unknown',
                    'external_code' => $salePrice['priceType']['externalCode'] ?? null,
                    'price'         => (int) ($salePrice['value'] ?? 0),
                    'product_id'    => $product->id,
                ];
            }, $salePrices);
        } else {
            // Цены из API (объекты)
            $priceTypes = array_map(function ($salePrice) use ($product) {
                return [
                    'external_id'   => $salePrice->priceType->id,
                    'name'          => $salePrice->priceType->name,
                    'external_code' => $salePrice->priceType->externalCode,
                    'price'         => (int) $salePrice->value,
                    'product_id'    => $product->id,
                ];
            }, $salePrices);
        }


        foreach ($priceTypes as $priceData) {
            $priceType = PriceType::query()->updateOrCreate(
                ['external_id' => $priceData['external_id']],
                [
                    'name'          => $priceData['name'],
                    'external_code' => $priceData['external_code'],
                ]
            );

            ProductPrice::query()->updateOrCreate(
                [
                    'type_id'       => $priceType->id,
                    'product_id'    => $product->id,
                ],
                ['price' => $priceData['price'] / 100]
            );
        }
    }

    /**
     * @param $attributes
     * @param $product
     *
     * @throws \JsonException
     */
    protected function processCategories($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::SITE_CATEGORY) {
                $categoryIds = $this->getCategoryIds(trim($attribute->value->name));
                $product->update(['category_id' => end($categoryIds)]);
            }
        }
    }

    /**
     * @param string $categoryPath
     *
     * @return array
     * @throws \JsonException
     * @throws \Exception
     */
    protected function getCategoryIds(string $categoryPath): array
    {
        $parts = array_map('trim', explode('>', $categoryPath));
        $parentId = null;
        $categoryIds = [];

        DB::beginTransaction(); // Начало транзакции
        try {
        foreach ($parts as $part) {

           $createSlug =  Str::slug($part);
            // Проверяем, существует ли категория с таким именем на всех языках (украинском, английском, русском)
            $category = Category::query()
                ->whereJsonContains('slug->uk', $createSlug)
                ->orWhereJsonContains('slug->en', $createSlug)
                ->orWhereJsonContains('slug->ru', $createSlug)
                ->first();

            // Если категория не найдена, создаем новую
            if (!$category) {
                $category = Category::create([
                    'name' => [
                        'uk' => $part,
                        'en' => $part,
                        'ru' => $part,
                    ],
                    'parent_id' => $parentId
                ]);
            }

            // Создаем слаг для каждой категории на разных языках
            $category->slug = [
                'en' => Str::slug($category->getTranslation('name', 'en')),
                'uk' => Str::slug($category->getTranslation('name', 'uk')),
                'ru' => Str::slug($category->getTranslation('name', 'ru'))
            ];
            $category->save();

            $parentId = $category->id;
            $categoryIds[] = $category->id;
        }

            DB::commit(); // Завершаем транзакцию
        } catch (\Exception $e) {
            DB::rollBack(); // Откатываем транзакцию в случае ошибки
            throw $e;
        }

        return $categoryIds;
    }

    protected function updateName($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::NAME) {
                $product->update([
                    'name' => [
                        'uk' => $attribute->value,
                        'ru' => $attribute->value,
                        'en' => $attribute->value,
                    ],
                ]);

                $product->slug = [
                    'en' => Str::slug($product->getTranslation('name', 'en')),
                    'uk' => Str::slug($product->getTranslation('name', 'uk')),
                    'ru' => Str::slug($product->getTranslation('name', 'ru'))
                ];

                $product->save();
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processExpenseCategory($attributes, $product): void
    {
        foreach ($attributes as $attribute) {

            if ($attribute->id === ProductAttributeEnum::CATEGORY_EXPENSES) {
                $expenseCategory = ExpenseCategory::query()->firstOrCreate(
                    ['name' => $attribute->value->name]
                );
                $product->detail()->updateOrCreate(
                    ['product_id' => $product->id],
                    ['expense_category_id' => $expenseCategory->id]
                );
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processBrand($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::BRAND) {
                $this->saveProductAttribute($attribute, $product, 'brand');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processRollSize($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::ROLL_SIZE) {
                $this->saveProductAttribute($attribute, $product, 'roll_size');
            }
        }
    }


    /**
     * @param $attributes
     * @param $product
     */
    protected function processFirstStock($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STOCK_QUANTITY_1) {
                $this->saveProductAttribute($attribute, $product, 'first_stock');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processSecondStock($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STOCK_QUANTITY_2) {
                $this->saveProductAttribute($attribute, $product, 'second_stock');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processThirdStock($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STOCK_QUANTITY_3) {
                $this->saveProductAttribute($attribute, $product, 'third_stock');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processThickness($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::THICKNESS) {
                $this->saveProductAttribute($attribute, $product, 'thickness');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processMaterialStretchingPercent($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MATERIAL_STRETCHING_PERCENT) {
                $this->saveProductAttribute($attribute, $product, 'material_stretching_percent');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processProtectionLiner($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::PROTECTIVE_LINER) {
                $this->saveProductAttribute($attribute, $product, 'protection_liner');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processMasterQualification($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MASTER_QUALIFICATION) {
                $this->saveProductAttribute($attribute, $product, 'master_qualification');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processAdhesion($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::ADHESION) {
                $this->saveProductAttribute($attribute, $product, 'adhesion');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processServiceLife($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::SERVICE_LIFE) {
                $this->saveProductAttribute($attribute, $product, 'service_life');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processWarranty($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::WARRANTY) {
                $this->saveProductAttribute($attribute, $product, 'warranty');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processProductTechnology($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::PRODUCTION_TECHNOLOGY) {
                $this->saveProductAttribute($attribute, $product, 'product_technology');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processQuantityStep($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::QUANTITY_STEP) {
                $this->saveProductAttribute($attribute, $product, 'quantity_step');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processMinOrderQuantity($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MINIMUM_ORDER_QUANTITY) {
                $this->saveProductAttribute($attribute, $product, 'min_order_quantity');
            }
        }
    }


    /**
     * @param $attributes
     * @param $product
     */
    protected function processStoreTerms($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STORAGE_TERM) {
                $this->saveProductAttribute($attribute, $product, 'store_terms');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processPropose($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::PURPOSE) {
                $this->saveProductAttribute($attribute, $product,'purpose');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processApplication($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::APPLICATION) {
                $this->saveProductAttribute($attribute, $product, 'application');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function updateBenefits($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::BENEFITS) {
                $this->saveProductAttribute($attribute, $product,'benefits');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function updateMaterial($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MATERIAL) {
                $this->saveProductAttribute($attribute, $product,'material');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function updateStructure($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STRUCTURE) {
                $this->saveProductAttribute($attribute, $product,'structure');
            }
        }
    }
    /**
     * @param $attributes
     * @param $product
     */
    protected function updateWidth($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::WIDTH_M) {
                $this->saveProductAttribute($attribute, $product,'width');
            }
        }
    }
    /**
     * @param $attributes
     * @param $product
     */
    protected function updateApplicationMethod($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::APPLICATION_METHOD) {
                $this->saveProductAttribute($attribute, $product,'application_method');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function updateSurfaceTemperature($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::SURFACE_TEMPERATURE) {
                $this->saveProductAttribute($attribute, $product,'surface_temperature');
            }
        }
    }


    /**
     * @param $attributes
     * @param $product
     */
    protected function updateOperatingTemperature($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::OPERATING_TEMPERATURE) {
                $this->saveProductAttribute($attribute, $product,'operating_temperature');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processRoomTemperature($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::ROOM_TEMPERATURE) {
                $this->saveProductAttribute($attribute, $product, 'room_temperature');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processMainShade($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MAIN_SHADE) {
              $this->saveProductAttribute($attribute, $product, 'main_shade');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     *
     * @throws \JsonException
     */
    protected function processType($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::TYPE) {
                $this->saveProductAttribute($attribute, $product, 'type');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processFormRelease($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::FORM_RELEASE) {
                $this->saveProductAttribute($attribute, $product, 'form_release');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processVolume($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::VOLUME) {
                $this->saveProductAttribute($attribute, $product, 'volume');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processUnderOrder($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::UNDER_ORDER) {
                $this->saveProductAttribute($attribute, $product, 'under_order');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processCountryManufacture($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::COUNTRY_OF_MANUFACTURER) {
                $this->saveProductAttribute($attribute, $product, 'country_manufacture');
            }
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processDefaultQuantity($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::DEFAULT_QUANTITY) {
                $this->saveProductAttribute($attribute, $product, 'default_quantity');
            }
        }
    }

    /**
     * @throws \JsonException
     */
    protected function saveProductAttribute($attribute, $product, $name): void
    {
        $productAttribute = Attribute::updateOrCreate(
            ['external_id' => $attribute->id],
            [
                'field_name' => $name,
                'name'       => [
                    'uk' => $attribute->name,
                    'ru' => $attribute->name,
                    'en' => $attribute->name,
                ],
            ]
        );

        $existingPivot = $product->attributes()->where('attribute_id', $productAttribute->id)->first();

        if ($existingPivot) {
            if (is_object($attribute->value)){
                $product->attributes()->updateExistingPivot($productAttribute->id, [
                    'value' => [
                        'uk' => $attribute->value?->name,
                        'ru' => $attribute->value?->name,
                        'en' => $attribute->value?->name,
                    ]
                ]);
            }
            else{
                $product->attributes()->updateExistingPivot($productAttribute->id, [
                    'value' => [
                        'uk' => $attribute->value,
                        'ru' => $attribute->value,
                        'en' => $attribute->value,
                    ]
                ]);
            }
        } else if (is_object($attribute->value)){
            $product->attributes()->attach($productAttribute->id, [
                'value' => [
                    'uk' => $attribute->value?->name,
                    'ru' => $attribute->value?->name,
                    'en' => $attribute->value?->name,
                ]
            ]);
        }
        else{
            $product->attributes()->attach($productAttribute->id, [
                'value' => [
                    'uk' => $attribute->value,
                    'ru' => $attribute->value,
                    'en' => $attribute->value,
                ]
            ]);
        }
    }

    /**
     * Генерирует конверсии для конкретного медиа файла
     */
    private function generateConversionsForMedia(Product $product, $mediaItem): void
    {
        try {
            $conversions = \App\Models\MediaConversions::getConversionsConfig();
            
            foreach ($conversions as $conversionName => $config) {
                if (in_array('images', $config['collections'])) {
                    $conversion = $product->addMediaConversion($conversionName);
                    
                    if ($config['width'] && $config['height']) {
                        $conversion->width($config['width'])->height($config['height']);
                    }
                    
                    if ($config['quality']) {
                        $conversion->quality($config['quality']);
                    }
                    
                    if ($config['sharpen']) {
                        $conversion->sharpen($config['sharpen']);
                    }
                    
                    if ($config['format']) {
                        $conversion->format($config['format']);
                    }
                    
                    if (isset($config['fit'])) {
                        $conversion->fit(\Spatie\Image\Enums\Fit::Contain);
                    }
                    
                    $conversion->optimize();
                    $conversion->nonQueued();
                    $conversion->performOnCollections('images');
                    
                    // Выполняем конверсию для конкретного медиа файла
                    // $conversion->performOnMedia($mediaItem);
                    
                    Log::info('Конверсия создана', [
                        'product_id' => $product->id,
                        'media_id' => $mediaItem->id,
                        'conversion_name' => $conversionName,
                        'file_name' => $mediaItem->file_name
                    ]);
                }
            }
            
            // Обновляем медиа файл, чтобы обновить generated_conversions
            $mediaItem->refresh();
            
            Log::info('Конверсии сгенерированы для изображения', [
                'product_id' => $product->id,
                'media_id' => $mediaItem->id,
                'file_name' => $mediaItem->file_name,
                'generated_conversions' => $mediaItem->getGeneratedConversions()->keys()->toArray()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Ошибка генерации конверсий', [
                'product_id' => $product->id,
                'media_id' => $mediaItem->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

}
