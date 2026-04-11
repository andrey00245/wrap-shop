<?php

namespace App\Services;

use App\Helpers\MoySkladApiHelper;
use App\Http\Enums\ProductAttributeEnum;
use App\Jobs\GalleryImageDownloadJob;
use App\Jobs\ProcessProductImages;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\ExpenseCategory;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\Products\Product as ApiProduct;
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
            'offset' => $offset,
            'maxResults' => $limit,
        ]))->getList();

        //        $jsonUrl = "https://api.moysklad.ru/api/remap/1.2/entity/currency/76e1fb94-76b8-11eb-0a80-00ab004bdad2";

        //        $response = Http::withHeaders([
        //            'Authorization'   => 'Basic ' . base64_encode(config('app.my_store.username').':'.config('app.my_store.password')),
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
                dd($e->getMessage(), $e->getTrace());
                dd($e->getLine(), $e->getMessage(), $e->getTraceAsString());
                Log::error('Ошибка при обработке продукта: '.$e->getMessage());
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
                'Authorization' => 'Basic '.$encodedCredentials,
                'Accept-Encoding' => 'gzip',
            ])->get($jsonUrl);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['rows']) && is_array($data['rows'])) {
                    $imageIndex = 0;
                    foreach ($data['rows'] as $image) {
                        $downloadUrl = $image['meta']['downloadHref'] ?? null;
                        if ($downloadUrl && filter_var($downloadUrl, FILTER_VALIDATE_URL)) {
                            $this->handleImageUpdate($product, $downloadUrl, $encodedCredentials, $imageIndex);
                            $imageIndex++;
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
            'Authorization' => 'Basic '.$encodedCredentials,
            'Accept-Encoding' => 'gzip',
        ])->get($downloadUrl);

        if ($imageResponse->successful()) {
            $fileContent = $imageResponse->body();
            $hash = md5($fileContent);
            $uniqueFilename = $hash.'.png';

            try {
                // Всегда создаём новое изображение, не заменяя по индексу (чтобы не терять старые при смене порядка)
                // Сначала регистрируем конверсии в модели продукта
                $product->registerMediaConversions();

                $mediaItem = $product->addMediaFromStream($fileContent)
                    ->usingFileName($uniqueFilename)
                    ->toMediaCollection('images');

                // Устанавливаем hash и порядок согласно порядку из МойСклад
                $mediaItem->setCustomProperty('moysklad_hash', $hash);
                $mediaItem->order_column = $imageIndex;
                $mediaItem->save();

                // Генерируем конверсии для конкретного медиа
                $this->generateConversionsForMedia($product, $mediaItem);

                Log::info('Изображение создано', [
                    'product_id' => $product->id,
                    'hash' => $hash,
                    'filename' => $uniqueFilename,
                    'media_id' => $mediaItem->id,
                    'image_index' => $imageIndex,
                    'generated_conversions' => $mediaItem->fresh()->getGeneratedConversions()->keys()->toArray(),
                ]);

            } catch (\Exception $e) {
                Log::error("Failed to update image for product ID {$product->external_id}. Error: {$e->getMessage()}");
            }
        } else {
            Log::error("Failed to download image from {$downloadUrl}. Status: {$imageResponse->status()}");
        }
    }

    /**
     * Отримання даних товару з МойСклад по external_id (для синхронізації цін/залишків).
     */
    public function fetchProductFromMoySklad(string $entityId): ?array
    {
        try {
            $response = MoySkladRemapHttp::basicAuthGet(
                "https://api.moysklad.ru/api/remap/1.2/entity/product/{$entityId}"
            );

            if ($response->successful()) {
                return $response->json();
            }

            $moyskladText = MoySkladApiHelper::formatErrorsFromResponse($response);
            Log::warning("МойСклад: товар не отримано — {$moyskladText}", [
                'entity_id' => $entityId,
                'status' => $response->status(),
                'moysklad_error' => $moyskladText,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('МойСклад: помилка отримання товару', [
                'entity_id' => $entityId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Синхронізація тільки цін та залишків товару з МойСклад (без зміни назв, атрибутів, зображень).
     */
    public function syncProductPricesAndStock(Product $product): bool
    {
        if (empty($product->external_id)) {
            Log::warning('Синхронізація цін/залишків: у товару немає external_id', ['product_id' => $product->id]);

            return false;
        }

        $productData = $this->fetchProductFromMoySklad($product->external_id);
        if (! $productData) {
            return false;
        }

        if (isset($productData['salePrices']) && is_array($productData['salePrices'])) {
            $this->processPrices($productData['salePrices'], $product);
        }

        $this->syncProductStockByCode($product);

        return true;
    }

    /**
     * Оновлює лише поле category_id з атрибута «Категорія сайту» в МойСклад (один GET /entity/product/{id}).
     * HTTP — через {@see fetchProductFromMoySklad} → {@see MoySkladRemapHttp} (пауза між запитами, ретраї 429), як у {@see syncProductPricesAndStock}.
     * Логіка поля — {@see processCategoriesFromWebhook}.
     */
    public function syncProductSiteCategoryFromMoySklad(Product $product): bool
    {
        if (empty($product->external_id)) {
            Log::warning('Синхронізація категорії: у товару немає external_id', ['product_id' => $product->id]);

            return false;
        }

        $productData = $this->fetchProductFromMoySklad($product->external_id);
        if (! $productData || empty($productData['attributes']) || ! is_array($productData['attributes'])) {
            return false;
        }

        try {
            $this->processCategoriesFromWebhook($productData['attributes'], $product);

            return true;
        } catch (\Throwable $e) {
            Log::error('Синхронізація категорії з МС: помилка', [
                'product_id' => $product->id,
                'external_id' => $product->external_id,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Оновлення цін і залишку одного товару через Bearer (MOY_SKLAD_TOKEN).
     * Основний синк лишається на Basic Auth — цей метод лише для тесту / окремого роута.
     *
     * @return array{ok: bool, message?: string, product_id?: int, external_id?: string, stock?: mixed, status?: int}
     */
    public function syncProductPricesAndStockWithBearer(Product $product): array
    {
        $token = trim((string) config('app.my_store.token'));
        if ($token === '') {
            return ['ok' => false, 'message' => 'MOY_SKLAD_TOKEN не заданий у .env'];
        }
        if (empty($product->external_id)) {
            return ['ok' => false, 'message' => 'У товару немає external_id'];
        }

        $response = MoySkladRemapHttp::bearerGet(
            $token,
            'https://api.moysklad.ru/api/remap/1.2/entity/product/'.$product->external_id
        );

        if (! $response->successful()) {
            return [
                'ok' => false,
                'message' => MoySkladApiHelper::formatErrorsFromResponse($response),
                'status' => $response->status(),
            ];
        }

        /** @var array<string, mixed> $productData */
        $productData = $response->json();
        if (isset($productData['salePrices']) && is_array($productData['salePrices'])) {
            $this->processPrices($productData['salePrices'], $product);
        }

        $codeFilter = rawurlencode((string) $product->code);
        $stockUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/assortment?filter=code~'.$codeFilter;
        $stockResp = MoySkladRemapHttp::bearerGet($token, $stockUrl);

        if (! $stockResp->successful()) {
            return [
                'ok' => false,
                'message' => 'Ціни оновлено, залишок не отримано: '.MoySkladApiHelper::formatErrorsFromResponse($stockResp),
                'status' => $stockResp->status(),
                'product_id' => $product->id,
            ];
        }

        $data = $stockResp->json();
        if (isset($data['rows'][0]['stock'])) {
            $product->update(['stock' => $data['rows'][0]['stock']]);
        } else {
            $product->update(['stock' => 0, 'is_active' => false]);
        }

        $product->refresh();

        return [
            'ok' => true,
            'message' => 'Оновлено ціни та залишок (Bearer)',
            'product_id' => $product->id,
            'external_id' => $product->external_id,
            'stock' => $product->stock,
        ];
    }

    /**
     * Синхронизация стока товара из МойСклад
     */
    public function syncProductStock(string $entityId): void
    {
        try {
            Log::info('Синхронизация стока товара', ['entity_id' => $entityId]);

            // Находим товар в нашей базе
            $product = \App\Models\Product::where('external_id', $entityId)->first();

            if ($product) {
                $this->syncProductStockByCode($product);

                Log::info('Сток товара синхронизирован', [
                    'product_id' => $product->id,
                    'entity_id' => $entityId,
                ]);
            } else {
                Log::warning('Товар не найден в базе', [
                    'entity_id' => $entityId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Ошибка синхронизации стока товара', [
                'entity_id' => $entityId,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Синхронизация стока товара по коду
     */
    public function syncProductStockByCode(\App\Models\Product $product): void
    {
        $codeFilter = rawurlencode((string) $product->code);
        $jsonUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/assortment?filter=code~'.$codeFilter;

        $response = MoySkladRemapHttp::basicAuthGet($jsonUrl);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['rows'][0]['stock'])) {
                $product->update(['stock' => $data['rows'][0]['stock']]);
                Log::info('Сток товара обновлен', [
                    'product_id' => $product->id,
                    'product_code' => $product->code,
                    'stock' => $data['rows'][0]['stock'],
                ]);
            } else {
                $product->update(['stock' => 0, 'is_active' => false]);
                Log::info('Сток товара установлен в 0, товар деактивовано (немає в МС)', [
                    'product_id' => $product->id,
                    'product_code' => $product->code,
                ]);
            }
        } else {
            Log::error('Ошибка получения стока товара', [
                'product_id' => $product->id,
                'product_code' => $product->code,
                'status' => $response->status(),
            ]);
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
                'product_code' => $productData['code'] ?? 'unknown',
            ]);

            DB::beginTransaction();

            // Для вебхуков синхронизируем товар напрямую без проверки атрибута "Сайт"
            $this->processProductFromWebhookData($productData);

            DB::commit();
            Log::info('Товар успешно синхронизирован из вебхука', [
                'product_id' => $productData['id'] ?? 'unknown',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка синхронизации товара из вебхука: '.$e->getMessage(), [
                'product_id' => $productData['id'] ?? 'unknown',
                'trace' => $e->getTraceAsString(),
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
            'product_code' => $productData['code'] ?? 'unknown',
        ]);

        // Проверяем атрибут "Сайт" как в оригинальном коде
        if (isset($productData['attributes'])) {
            $hasSiteAttribute = false;
            $siteAttributeValue = null;

            foreach ($productData['attributes'] as $attribute) {
                if (isset($attribute['id']) && $attribute['id'] == ProductAttributeEnum::SITE) {
                    $siteAttributeValue = $attribute['value']['name'] ?? $attribute['value'] ?? null;
                    $normalized = is_string($siteAttributeValue) ? mb_strtolower(trim($siteAttributeValue)) : null;
                    if ($normalized === 'так') {
                        $hasSiteAttribute = true;
                        break;
                    }
                }
            }

            Log::info('Проверка атрибута "Сайт"', [
                'product_id' => $productData['id'] ?? 'unknown',
                'has_site_attribute' => $hasSiteAttribute,
                'site_attribute_value' => $siteAttributeValue,
                'total_attributes' => count($productData['attributes']),
            ]);

            // Проверяем, существует ли товар
            $existingProduct = \App\Models\Product::where('external_id', $productData['id'])->first();

            if (! $hasSiteAttribute) {
                // Если товар есть, но атрибут "Сайт" = "ні" - деактивируем
                if ($existingProduct) {
                    $existingProduct->update(['is_active' => false]);
                    Log::info('Товар деактивирован - атрибут "Сайт" = "ні"', [
                        'product_id' => $existingProduct->id,
                        'external_id' => $productData['id'],
                    ]);
                } else {
                    Log::info('Товар не имеет атрибута "Сайт" со значением "так" - пропускаем', [
                        'product_id' => $productData['id'] ?? 'unknown',
                        'site_value' => $siteAttributeValue,
                    ]);
                }

                return;
            } else {
                // Если товар был деактивирован, но теперь атрибут "Сайт" = "так" - активируем
                if ($existingProduct && ! $existingProduct->is_active) {
                    $existingProduct->update(['is_active' => true]);
                    Log::info('Товар активирован - атрибут "Сайт" = "так"', [
                        'product_id' => $existingProduct->id,
                        'external_id' => $productData['id'],
                    ]);
                }
            }
        } else {
            Log::warning('Товар не имеет атрибутов - пропускаем', [
                'product_id' => $productData['id'] ?? 'unknown',
            ]);

            return;
        }

        // $existingProduct уже определен выше
        $isNewProduct = ! $existingProduct;

        if ($isNewProduct) {
            // Новый продукт - создаем все 3 локализации
            $product = \App\Models\Product::create([
                'external_id' => $productData['id'],
                'external_code' => $productData['externalCode'] ?? null,
                'code' => $productData['code'] ?? null,
                'article' => $productData['article'] ?? null,
                'is_active' => 1, // Активируем товар при создании из вебхука
                'name' => [
                    'ru' => $productData['name'] ?? '',
                    'uk' => $productData['name'] ?? '',
                    'en' => $productData['name'] ?? '',
                ],
                'descriptions' => [
                    'ru' => $productData['description'] ?? '',
                    'uk' => $productData['description'] ?? '',
                    'en' => $productData['description'] ?? '',
                ],
            ]);

            Log::info('Создан новый продукт из вебхука', [
                'external_id' => $productData['id'],
                'code' => $productData['code'] ?? null,
            ]);
        } else {
            // Старый продукт - обновляем только UK значения переводов
            $product = $existingProduct;
            $product->update([
                'external_code' => $productData['externalCode'] ?? $product->external_code,
                'code' => $productData['code'] ?? $product->code,
                'article' => $productData['article'] ?? $product->article,
                'name' => [
                    'uk' => $productData['name'] ?? $product->getTranslation('name', 'uk'),
                    // ru и en остаются как есть
                ],
                'descriptions' => [
                    'uk' => $productData['description'] ?? $product->getTranslation('descriptions', 'uk'),
                    // ru и en остаются как есть
                ],
            ]);

            Log::info('Обновлен существующий продукт из вебхука (только UK переводы)', [
                'external_id' => $productData['id'],
                'code' => $productData['code'] ?? null,
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
                'ru' => \Illuminate\Support\Str::slug($ruName),
            ];
        } else {
            // Для существующих товаров - обновляем только UK слаг
            $currentSlug = $product->slug ?? [];

            Log::info('Обработка слага для существующего товара', [
                'product_id' => $product->id,
                'current_slug_type' => gettype($currentSlug),
                'current_slug_value' => $currentSlug,
            ]);

            // Проверяем, что slug является массивом
            if (! is_array($currentSlug)) {
                // Если slug - строка, создаем массив с этой строкой как UK слагом
                $currentSlug = ['uk' => $currentSlug];
                Log::info('Преобразован строковый слаг в массив', [
                    'product_id' => $product->id,
                    'converted_slug' => $currentSlug,
                ]);
            }

            // Получаем название товара на украинском
            $ukName = $product->getTranslation('name', 'uk');
            if (empty($ukName)) {
                $ukName = $product->name ?? 'product';
                Log::warning('Название товара на украинском пустое, используем общее название', [
                    'product_id' => $product->id,
                    'fallback_name' => $ukName,
                ]);
            }

            $newSlug = array_merge($currentSlug, [
                'uk' => \Illuminate\Support\Str::slug($ukName),
            ]);

            Log::info('Обновлен слаг товара', [
                'product_id' => $product->id,
                'new_slug' => $newSlug,
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
            'external_id' => $product->external_id,
        ]);
    }

    /**
     * Обработка атрибутов товара из вебхука
     */
    private function processAttributesFromWebhook(array $attributes, Product $product): void
    {
        Log::info('Обработка атрибутов товара из вебхука', [
            'product_id' => $product->id,
            'attributes_count' => count($attributes),
        ]);

        $receivedExternalIds = [];
        $receivedAttributesForLog = [];

        foreach ($attributes as $attribute) {
            try {
                if (isset($attribute['id'])) {
                    $receivedExternalIds[] = $attribute['id'];
                }
                $receivedAttributesForLog[] = [
                    'id' => $attribute['id'] ?? null,
                    'name' => $attribute['name'] ?? null,
                    'value' => $attribute['value'] ?? null,
                ];
                $this->processAttributeFromWebhook($attribute, $product);
            } catch (\Exception $e) {
                Log::error('Ошибка обработки атрибута из вебхука', [
                    'product_id' => $product->id,
                    'attribute_id' => $attribute['id'] ?? 'unknown',
                    'attribute_name' => $attribute['name'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Список атрибутов из вебхука', [
            'product_id' => $product->id,
            'attributes' => $receivedAttributesForLog,
        ]);

        // Синхронизируем коллекцию banner_images с текущим набором URL из атрибутов Доп.галерея
        try {
            $galleryUrls = $this->extractAdditionalGalleryUrls($attributes);
            $this->syncBannerImagesFromGalleryUrls($product, $galleryUrls);
        } catch (\Throwable $e) {
            Log::error('Ошибка синхронизации banner_images по Доп.галерея', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Синхронизируем атрибуты: убираем у товара те, которых нет в текущем вебхуке (только известные по enum)
        try {
            $this->syncDetachedAttributes($product, $receivedExternalIds);
        } catch (\Throwable $e) {
            Log::error('Ошибка синхронизации (удаления) атрибутов, отсутствующих в вебхуке', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
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

        if (! $attributeId) {
            return;
        }

        // Пустое значение из вебхука — считаем как удаление привязки атрибута у товара
        if ($attributeValue === null || $attributeValue === '' || (is_array($attributeValue) && empty($attributeValue))) {
            try {
                $attr = Attribute::where('external_id', $attributeId)->first();
                if ($attr) {
                    $product->attributes()->detach($attr->id);
                    Log::info('Атрибут удален (пустое значение из вебхука)', [
                        'product_id' => $product->id,
                        'attribute_id' => $attributeId,
                        'name' => $attributeName,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Ошибка удаления атрибута (пустое значение)', [
                    'product_id' => $product->id,
                    'attribute_id' => $attributeId,
                    'error' => $e->getMessage(),
                ]);
            }

            return;
        }

        // Создаем атрибут при первом появлении
        $productAttribute = Attribute::firstOrCreate(
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

        // Обновляем только украинское имя, сохраняя существующие переводы ru/en
        try {
            $existingNames = method_exists($productAttribute, 'getTranslations')
                ? ($productAttribute->getTranslations('name') ?? [])
                : ($productAttribute->name ?? []);

            if (! is_array($existingNames)) {
                $existingNames = [];
            }
            $existingNames['uk'] = $attributeName;
            $productAttribute->update(['name' => $existingNames]);
        } catch (\Throwable $e) {
            // fallback: хотя бы uk
            $productAttribute->update(['name' => ['uk' => $attributeName]]);
        }

        // Если ранее поле было custom_*, а у нас есть маппинг — обновляем field_name
        $mappedField = $this->getFieldNameByAttributeId($attributeId);
        if (
            $mappedField !== ('custom_'.$attributeId)
            && (str_starts_with($productAttribute->field_name, 'custom_'))
        ) {
            $productAttribute->update(['field_name' => $mappedField]);
        }

        // Определяем значение атрибута (поддержка строк/чисел/булевых)
        $value = null;
        if (is_array($attributeValue) && isset($attributeValue['name'])) {
            $value = $attributeValue['name'];
        } elseif (is_scalar($attributeValue)) { // строки, числа, bool
            $value = $attributeValue;
        }

        if ($value !== null) {
            // Для всех булевых значений сохраняем как булевые, не как строки
            // Это работает для всех атрибутов, не только для UNDER_ORDER
            $isBooleanValue = is_bool($value);

            // Обновляем или создаем связь с товаром
            $existingPivot = $product->attributes()->where('attribute_id', $productAttribute->id)->first();

            // Мерджим pivot->value: обновляем только uk, ru/en сохраняем
            if ($existingPivot && isset($existingPivot->pivot)) {
                $currentVal = $existingPivot->pivot->value;
                if (! is_array($currentVal)) {
                    $currentVal = ['uk' => $currentVal]; // Keep original type for non-array
                }
                $currentVal['uk'] = $value; // Assign value directly, preserving type
                $product->attributes()->updateExistingPivot($productAttribute->id, ['value' => $currentVal]);
            } else {
                $product->attributes()->attach($productAttribute->id, [
                    'value' => [
                        'uk' => $value, // Assign value directly, preserving type
                    ],
                ]);
            }

            Log::info('Атрибут обработан из вебхука', [
                'product_id' => $product->id,
                'attribute_id' => $attributeId,
                'attribute_name' => $attributeName,
                'value' => $value,
                'value_type' => gettype($value),
                'is_boolean' => is_bool($value),
                'is_under_order' => ($attributeId === ProductAttributeEnum::UNDER_ORDER),
            ]);

            // Обработка атрибутов Доп.галерея1..8 → сохранение в коллекцию banner_images
            if ($this->isAdditionalGalleryAttribute($attributeName)) {
                $this->saveBannerImageFromUrl($product, $value, $attributeName);
            }
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
            ProductAttributeEnum::SERIES => 'series',
            ProductAttributeEnum::TYPE => 'type',
            ProductAttributeEnum::PURPOSE => 'purpose',
            ProductAttributeEnum::MATERIAL => 'material',
            ProductAttributeEnum::STRUCTURE => 'structure',
            ProductAttributeEnum::MAIN_SHADE => 'main_shade',
            ProductAttributeEnum::THICKNESS => 'thickness',
            ProductAttributeEnum::WIDTH_M => 'width',
            ProductAttributeEnum::ROLL_SIZE => 'roll_size',
            ProductAttributeEnum::APPLICATION => 'application',
            ProductAttributeEnum::APPLICATION_METHOD => 'application_method',
            ProductAttributeEnum::BENEFITS => 'benefits',
            ProductAttributeEnum::FORM_RELEASE => 'form_release',
            ProductAttributeEnum::VOLUME => 'volume',
            ProductAttributeEnum::OPERATING_TEMPERATURE => 'operating_temperature',
            ProductAttributeEnum::SURFACE_TEMPERATURE => 'surface_temperature',
            ProductAttributeEnum::ROOM_TEMPERATURE => 'room_temperature',
            ProductAttributeEnum::ADHESION => 'adhesion',
            ProductAttributeEnum::SERVICE_LIFE => 'service_life',
            ProductAttributeEnum::STORAGE_TERM => 'store_terms',
            ProductAttributeEnum::WARRANTY => 'warranty',
            ProductAttributeEnum::PRODUCTION_TECHNOLOGY => 'production_technology',
            ProductAttributeEnum::MATERIAL_STRETCHING_PERCENT => 'material_stretching_percent',
            ProductAttributeEnum::PROTECTIVE_LINER => 'protective_liner',
            ProductAttributeEnum::MASTER_QUALIFICATION => 'master_qualification',
            ProductAttributeEnum::COUNTRY_OF_MANUFACTURER => 'country_manufacture',
            ProductAttributeEnum::DEFAULT_QUANTITY => 'default_quantity',
            ProductAttributeEnum::QUANTITY_STEP => 'quantity_step',
            ProductAttributeEnum::MINIMUM_ORDER_QUANTITY => 'min_order_quantity',
            ProductAttributeEnum::STOCK_QUANTITY_1 => 'first_stock',
            ProductAttributeEnum::STOCK_QUANTITY_2 => 'second_stock',
            ProductAttributeEnum::STOCK_QUANTITY_3 => 'third_stock',
            ProductAttributeEnum::UNDER_ORDER => 'under_order',
        ];

        return $fieldMap[$attributeId] ?? 'custom_'.$attributeId;
    }

    private function syncDetachedAttributes(Product $product, array $receivedExternalIds): void
    {
        $attached = $product->attributes()
            ->select('attributes.id', 'attributes.external_id', 'attributes.field_name')
            ->get();

        // Детачим любые атрибуты, пришедшие из МС (имеют external_id), если их external_id нет в текущем вебхуке
        $toDetachIds = $attached->filter(function ($attr) use ($receivedExternalIds) {
            if (empty($attr->external_id)) {
                return false; // локальные/пользовательские не трогаем
            }

            return ! in_array($attr->external_id, $receivedExternalIds, true);
        })->pluck('id')->all();

        if (! empty($toDetachIds)) {
            $product->attributes()->detach($toDetachIds);
            Log::info('Удалены атрибуты, отсутствующие в текущем вебхуке', [
                'product_id' => $product->id,
                'detached_attribute_ids' => $toDetachIds,
            ]);
        }
    }

    /**
     * Проверка, является ли атрибут пунктом дополнительной галереи (Доп.галерея1..8)
     */
    private function isAdditionalGalleryAttribute(string $attributeName): bool
    {
        // Нормализуем пробелы и регистр
        $normalized = mb_strtolower(trim($attributeName));
        // Допускаем варианты с точкой и без после "Доп"
        // Примеры: "Доп.галерея1", "Доп.галерея 2", "доп.галерея3"
        if (! str_starts_with($normalized, 'доп.галерея')) {
            return false;
        }

        // Извлекаем номер при наличии
        $num = (int) preg_replace('/[^0-9]/u', '', $normalized);

        return $num >= 1 && $num <= 8;
    }

    /**
     * Сохранение изображения баннера по URL в коллекцию banner_images с дедупликацией
     */
    private function saveBannerImageFromUrl(Product $product, string $url, string $attributeName): void
    {
        try {
            if (! filter_var($url, FILTER_VALIDATE_URL)) {
                Log::warning('URL дополнительной галереи некорректен', [
                    'product_id' => $product->id,
                    'attribute' => $attributeName,
                    'url' => $url,
                ]);

                return;
            }

            // Скачиваем файл
            $response = Http::withHeaders(['Accept-Encoding' => 'gzip'])->get($url);
            if (! $response->successful()) {
                Log::warning('Не удалось скачать изображение дополнительной галереи', [
                    'product_id' => $product->id,
                    'attribute' => $attributeName,
                    'status' => $response->status(),
                ]);

                return;
            }

            $content = $response->body();
            if ($content === '' || $content === null) {
                Log::warning('Пустой ответ при скачивании изображения дополнительной галереи', [
                    'product_id' => $product->id,
                    'attribute' => $attributeName,
                ]);

                return;
            }

            $hash = md5($content);

            // Дедупликация: проверяем по хешу или исходному URL в кастомных свойствах
            $alreadyExists = $product->getMedia('banner_images')
                ->first(function ($media) use ($hash, $url) {
                    $sourceHash = $media->getCustomProperty('source_hash');
                    $sourceUrl = $media->getCustomProperty('source_url');

                    return ($sourceHash && $sourceHash === $hash) || ($sourceUrl && $sourceUrl === $url);
                });

            if ($alreadyExists) {
                Log::info('Изображение из дополнительной галереи уже существует, пропуск', [
                    'product_id' => $product->id,
                    'attribute' => $attributeName,
                    'url' => $url,
                ]);

                return;
            }

            // Определяем имя файла по URL или по хешу
            $parsed = parse_url($url);
            $filename = 'banner_'.($parsed['path'] ?? 'image').'_'.substr($hash, 0, 8).'.jpg';
            $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $filename);

            // Сохраняем в медиаколлекцию
            $product
                ->addMediaFromString($content)
                ->usingFileName($filename)
                ->withCustomProperties([
                    'source_url' => $url,
                    'source_hash' => $hash,
                    'source' => 'webhook_additional_gallery',
                    'attribute' => $attributeName,
                ])
                ->toMediaCollection('banner_images');

            Log::info('Изображение из дополнительной галереи сохранено', [
                'product_id' => $product->id,
                'attribute' => $attributeName,
                'url' => $url,
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка сохранения изображения из дополнительной галереи', [
                'product_id' => $product->id,
                'attribute' => $attributeName,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Получаем список URL из атрибутов Доп.галерея1..8
     */
    private function extractAdditionalGalleryUrls(array $attributes): array
    {
        $urls = [];
        foreach ($attributes as $attribute) {
            $name = $attribute['name'] ?? '';
            if (! $name || ! $this->isAdditionalGalleryAttribute($name)) {
                continue;
            }
            $rawValue = $attribute['value'] ?? null;
            $value = null;
            if (is_array($rawValue) && isset($rawValue['name'])) {
                $value = $rawValue['name'];
            } elseif (is_string($rawValue)) {
                $value = $rawValue;
            }
            if ($value && filter_var($value, FILTER_VALIDATE_URL)) {
                $urls[] = $value;
            }
        }

        // Уникальные
        return array_values(array_unique($urls));
    }

    /**
     * Удаляем из коллекции banner_images файлы, которые были добавлены из Доп.галерея, но более не присутствуют в атрибутах
     */
    private function syncBannerImagesFromGalleryUrls(Product $product, array $actualUrls): void
    {
        $actualSet = array_flip($actualUrls);
        $deleted = 0;
        foreach ($product->getMedia('banner_images') as $media) {
            $source = $media->getCustomProperty('source');
            $url = $media->getCustomProperty('source_url');
            // Удаляем только те, что были добавлены нашим обработчиком и имеют исходный URL
            if ($source === 'webhook_additional_gallery' && $url && ! isset($actualSet[$url])) {
                $media->delete();
                $deleted++;
            }
        }
        if ($deleted > 0) {
            Log::info('Синхронизация banner_images: удалены устаревшие изображения', [
                'product_id' => $product->id,
                'deleted' => $deleted,
            ]);
        }
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
                            'category_id' => end($categoryIds),
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Ошибка обработки категории из вебхука', [
                            'product_id' => $product->id,
                            'category_path' => $categoryPath,
                            'error' => $e->getMessage(),
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
                'images_url' => $imagesUrl,
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

                    // Если есть старые изображения без moysklad_hash — считаем их "старым форматом" и просто чистим коллекцию
                    $hasImagesWithoutHash = $existingImages->contains(function ($media) {
                        return ! $media->getCustomProperty('moysklad_hash');
                    });

                    if ($hasImagesWithoutHash) {
                        try {
                            $countBefore = $existingImages->count();
                            $product->clearMediaCollection('images');
                            Log::info('Очищена коллекция изображений (старый формат без hash)', [
                                'product_id' => $product->id,
                                'deleted_count' => $countBefore,
                            ]);
                            // после очистки пересобираем коллекции
                            $existingImages = $product->getMedia('images');
                            $existingHashes = [];
                        } catch (\Throwable $e) {
                            Log::warning('Не удалось очистить коллекцию изображений', [
                                'product_id' => $product->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    } else {
                        // Удаляем дубликаты среди уже сохранённых (один media на каждый hash)
                        try {
                            $seen = [];
                            $deletedDuplicates = 0;
                            foreach ($existingImages as $media) {
                                $hash = $media->getCustomProperty('moysklad_hash');
                                if (! $hash) {
                                    continue;
                                }
                                if (isset($seen[$hash])) {
                                    $media->delete();
                                    $deletedDuplicates++;

                                    continue;
                                }
                                $seen[$hash] = true;
                            }
                            if ($deletedDuplicates > 0) {
                                Log::info('Удалены дубликаты изображений по hash', [
                                    'product_id' => $product->id,
                                    'deleted' => $deletedDuplicates,
                                ]);
                                // Обновляем коллекцию и хеши после чистки
                                $existingImages = $product->getMedia('images');
                                $existingHashes = $existingImages->map(function ($media) {
                                    return $media->getCustomProperty('moysklad_hash');
                                })->filter()->toArray();
                            }
                        } catch (\Throwable $e) {
                            Log::warning('Не удалось удалить дубликаты изображений', [
                                'product_id' => $product->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

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

                    // Удаляем изображения, которых нет в МойСклад, ТОЛЬКО если в ответе есть хотя бы одно изображение
                    $deletedImagesCount = 0;
                    if (count($imagesData['rows']) > 0) {
                        foreach ($existingImages as $media) {
                            $mediaHash = $media->getCustomProperty('moysklad_hash');
                            if ($mediaHash && ! in_array($mediaHash, $moyskladHashes)) {
                                $media->delete();
                                $deletedImagesCount++;
                                Log::info('Изображение удалено (отсутствует в МойСклад)', [
                                    'product_id' => $product->id,
                                    'media_id' => $media->id,
                                    'hash' => $mediaHash,
                                ]);
                            }
                        }
                    } else {
                        Log::info('Удаление изображений пропущено: в ответе МойСклад нет изображений', [
                            'product_id' => $product->id,
                        ]);
                    }

                    $updatedImagesCount = 0;
                    $skippedImagesCount = 0;
                    $imageIndex = 0;

                    foreach ($imagesData['rows'] as $image) {
                        $downloadUrl = $image['meta']['downloadHref'] ?? null;
                        if ($downloadUrl && filter_var($downloadUrl, FILTER_VALIDATE_URL)) {
                            // Если такого хеша ещё нет — добавляем; если есть — просто пересетим порядок позже
                            if ($this->shouldUpdateImage($product, $downloadUrl, $existingHashes)) {
                                $this->handleImageUpdate($product, $downloadUrl, base64_encode(config('app.my_store.username').':'.config('app.my_store.password')), $imageIndex);
                                $updatedImagesCount++;
                            } else {
                                $skippedImagesCount++;
                            }
                            $imageIndex++;
                        }
                    }

                    // После добавления новых изображений — выравниваем порядок изображений согласно порядку МойСклад
                    try {
                        $currentMedia = $product->getMedia('images');
                        foreach ($moyskladHashes as $idx => $hash) {
                            $media = $currentMedia->first(function ($m) use ($hash) {
                                return $m->getCustomProperty('moysklad_hash') === $hash;
                            });
                            if ($media && $media->order_column !== $idx) {
                                $media->order_column = $idx;
                                $media->save();
                            }
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Не удалось выровнять порядок изображений', [
                            'product_id' => $product->id,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    Log::info('Изображения товара обработаны из вебхука', [
                        'product_id' => $product->id,
                        'total_images' => count($imagesData['rows']),
                        'updated_images' => $updatedImagesCount,
                        'skipped_images' => $skippedImagesCount,
                        'deleted_images' => $deletedImagesCount,
                        'existing_images' => $existingImages->count(),
                    ]);
                }
            } else {
                Log::error('Ошибка получения изображений из вебхука', [
                    'product_id' => $product->id,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Исключение при обработке изображений из вебхука', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
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
                        'download_url' => $downloadUrl,
                    ]);

                    return false;
                }

                Log::info('Изображение изменилось, обновляем', [
                    'product_id' => $product->id,
                    'hash' => $hash,
                    'download_url' => $downloadUrl,
                ]);

                return true;
            }
        } catch (\Exception $e) {
            Log::warning('Ошибка проверки изображения, скачиваем', [
                'product_id' => $product->id,
                'download_url' => $downloadUrl,
                'error' => $e->getMessage(),
            ]);
        }

        return true; // В случае ошибки скачиваем изображение
    }

    protected function processProduct($item): void
    {
        $parseData = $item->jsonSerialize();

        if (property_exists($parseData, 'attributes')) {
            $attributes = $parseData?->attributes?->attrs;

            $product = null;

            foreach ($attributes as $attribute) {
                if ($attribute->id === ProductAttributeEnum::SITE) {
                    if ($attribute->value->name === 'так') {
                        // Проверяем, существует ли продукт
                        $existingProduct = Product::where('external_id', $parseData->id)->first();
                        $isNewProduct = ! $existingProduct;

                        if ($isNewProduct) {
                            $product = Product::create([
                                'external_id' => $parseData->id,
                                'external_code' => $parseData->externalCode,
                                'code' => $parseData->code,
                                'article' => $parseData->article ?? null,
                                'name' => [
                                    'ru' => $parseData->name ?? '',
                                    'uk' => $parseData->name ?? '',
                                    'en' => $parseData->name ?? '',
                                ],
                                'descriptions' => [
                                    'ru' => $parseData->description ?? '',
                                    'uk' => $parseData->description ?? '',
                                    'en' => $parseData->description ?? '',
                                ],
                            ]);

                            Log::info('Создан новый продукт', [
                                'external_id' => $parseData->id,
                                'code' => $parseData->code,
                            ]);
                        } else {
                            // Старый продукт - обновляем только UK локализацию
                            $product = $existingProduct;
                            $product->update([
                                'external_code' => $parseData->externalCode,
                                'code' => $parseData->code,
                                'article' => $parseData->article ?? null,
                                'name' => [
                                    'uk' => $parseData->name ?? $product->getTranslation('name', 'uk'),
                                ],
                                'descriptions' => [
                                    'uk' => $parseData->description ?? $product->getTranslation('descriptions', 'uk'),
                                ],
                            ]);

                            Log::info('Обновлен существующий продукт (только UK)', [
                                'external_id' => $parseData->id,
                                'code' => $parseData->code,
                            ]);
                        }

                        $product->slug = [
                            'en' => Str::slug($product->getTranslation('name', 'en')),
                            'uk' => Str::slug($product->getTranslation('name', 'uk')),
                            'ru' => Str::slug($product->getTranslation('name', 'ru')),
                        ];

                        $product->save();

                        $this->processPrices($parseData->salePrices, $product);
                    } else {
                        return;
                    }
                }
            }

            if (! $product) {
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

            if ($hasGallery) {
                $product->update([
                    'banner_title' => [
                        'en' => 'Look at how this film will look on the car',
                        'uk' => 'Подивіться, як виглядатиме ця плівка на автомобілі',
                        'ru' => 'Посмотрите, как будет выглядеть эта пленка на автомобиле',
                    ],
                ]);
            }
        }
    }

    protected function saveGalleryLink($attribute, $product): void
    {
        if (filter_var($attribute->value, FILTER_VALIDATE_URL)) {
            GalleryImageDownloadJob::dispatch($attribute->value, $product);
        }
    }

    protected function processPrices($salePrices, $product): void
    {
        // Обрабатываем цены из вебхука (массив) или из API (объекты)
        if (is_array($salePrices) && isset($salePrices[0]) && is_array($salePrices[0])) {
            // Цены из вебхука
            $priceTypes = array_map(function ($salePrice) use ($product) {
                return [
                    'external_id' => $salePrice['priceType']['id'] ?? null,
                    'name' => $salePrice['priceType']['name'] ?? 'Unknown',
                    'external_code' => $salePrice['priceType']['externalCode'] ?? null,
                    'price' => (int) ($salePrice['value'] ?? 0),
                    'product_id' => $product->id,
                ];
            }, $salePrices);
        } else {
            // Цены из API (объекты)
            $priceTypes = array_map(function ($salePrice) use ($product) {
                return [
                    'external_id' => $salePrice->priceType->id,
                    'name' => $salePrice->priceType->name,
                    'external_code' => $salePrice->priceType->externalCode,
                    'price' => (int) $salePrice->value,
                    'product_id' => $product->id,
                ];
            }, $salePrices);
        }

        foreach ($priceTypes as $priceData) {
            $priceType = PriceType::query()->updateOrCreate(
                ['external_id' => $priceData['external_id']],
                [
                    'name' => $priceData['name'],
                    'external_code' => $priceData['external_code'],
                ]
            );

            ProductPrice::query()->updateOrCreate(
                [
                    'type_id' => $priceType->id,
                    'product_id' => $product->id,
                ],
                ['price' => $priceData['price'] / 100]
            );
        }
    }

    /**
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

                $createSlug = Str::slug($part);
                // Проверяем, существует ли категория с таким именем на всех языках (украинском, английском, русском)
                $category = Category::query()
                    ->whereJsonContains('slug->uk', $createSlug)
                    ->orWhereJsonContains('slug->en', $createSlug)
                    ->orWhereJsonContains('slug->ru', $createSlug)
                    ->first();

                // Если категория не найдена, создаем новую
                if (! $category) {
                    $category = Category::create([
                        'name' => [
                            'uk' => $part,
                            'en' => $part,
                            'ru' => $part,
                        ],
                        'parent_id' => $parentId,
                    ]);
                }

                // Создаем слаг для каждой категории на разных языках
                $category->slug = [
                    'en' => Str::slug($category->getTranslation('name', 'en')),
                    'uk' => Str::slug($category->getTranslation('name', 'uk')),
                    'ru' => Str::slug($category->getTranslation('name', 'ru')),
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
                    'ru' => Str::slug($product->getTranslation('name', 'ru')),
                ];

                $product->save();
            }
        }
    }

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

    protected function processBrand($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::BRAND) {
                $this->saveProductAttribute($attribute, $product, 'brand');
            }
        }
    }

    protected function processRollSize($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::ROLL_SIZE) {
                $this->saveProductAttribute($attribute, $product, 'roll_size');
            }
        }
    }

    protected function processFirstStock($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STOCK_QUANTITY_1) {
                $this->saveProductAttribute($attribute, $product, 'first_stock');
            }
        }
    }

    protected function processSecondStock($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STOCK_QUANTITY_2) {
                $this->saveProductAttribute($attribute, $product, 'second_stock');
            }
        }
    }

    protected function processThirdStock($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STOCK_QUANTITY_3) {
                $this->saveProductAttribute($attribute, $product, 'third_stock');
            }
        }
    }

    protected function processThickness($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::THICKNESS) {
                $this->saveProductAttribute($attribute, $product, 'thickness');
            }
        }
    }

    protected function processMaterialStretchingPercent($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MATERIAL_STRETCHING_PERCENT) {
                $this->saveProductAttribute($attribute, $product, 'material_stretching_percent');
            }
        }
    }

    protected function processProtectionLiner($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::PROTECTIVE_LINER) {
                $this->saveProductAttribute($attribute, $product, 'protection_liner');
            }
        }
    }

    protected function processMasterQualification($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MASTER_QUALIFICATION) {
                $this->saveProductAttribute($attribute, $product, 'master_qualification');
            }
        }
    }

    protected function processAdhesion($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::ADHESION) {
                $this->saveProductAttribute($attribute, $product, 'adhesion');
            }
        }
    }

    protected function processServiceLife($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::SERVICE_LIFE) {
                $this->saveProductAttribute($attribute, $product, 'service_life');
            }
        }
    }

    protected function processWarranty($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::WARRANTY) {
                $this->saveProductAttribute($attribute, $product, 'warranty');
            }
        }
    }

    protected function processProductTechnology($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::PRODUCTION_TECHNOLOGY) {
                $this->saveProductAttribute($attribute, $product, 'product_technology');
            }
        }
    }

    protected function processQuantityStep($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::QUANTITY_STEP) {
                $this->saveProductAttribute($attribute, $product, 'quantity_step');
            }
        }
    }

    protected function processMinOrderQuantity($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MINIMUM_ORDER_QUANTITY) {
                $this->saveProductAttribute($attribute, $product, 'min_order_quantity');
            }
        }
    }

    protected function processStoreTerms($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STORAGE_TERM) {
                $this->saveProductAttribute($attribute, $product, 'store_terms');
            }
        }
    }

    protected function processPropose($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::PURPOSE) {
                $this->saveProductAttribute($attribute, $product, 'purpose');
            }
        }
    }

    protected function processApplication($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::APPLICATION) {
                $this->saveProductAttribute($attribute, $product, 'application');
            }
        }
    }

    protected function updateBenefits($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::BENEFITS) {
                $this->saveProductAttribute($attribute, $product, 'benefits');
            }
        }
    }

    protected function updateMaterial($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MATERIAL) {
                $this->saveProductAttribute($attribute, $product, 'material');
            }
        }
    }

    protected function updateStructure($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::STRUCTURE) {
                $this->saveProductAttribute($attribute, $product, 'structure');
            }
        }
    }

    protected function updateWidth($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::WIDTH_M) {
                $this->saveProductAttribute($attribute, $product, 'width');
            }
        }
    }

    protected function updateApplicationMethod($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::APPLICATION_METHOD) {
                $this->saveProductAttribute($attribute, $product, 'application_method');
            }
        }
    }

    protected function updateSurfaceTemperature($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::SURFACE_TEMPERATURE) {
                $this->saveProductAttribute($attribute, $product, 'surface_temperature');
            }
        }
    }

    protected function updateOperatingTemperature($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::OPERATING_TEMPERATURE) {
                $this->saveProductAttribute($attribute, $product, 'operating_temperature');
            }
        }
    }

    protected function processRoomTemperature($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::ROOM_TEMPERATURE) {
                $this->saveProductAttribute($attribute, $product, 'room_temperature');
            }
        }
    }

    protected function processMainShade($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::MAIN_SHADE) {
                $this->saveProductAttribute($attribute, $product, 'main_shade');
            }
        }
    }

    /**
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

    protected function processFormRelease($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::FORM_RELEASE) {
                $this->saveProductAttribute($attribute, $product, 'form_release');
            }
        }
    }

    protected function processVolume($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::VOLUME) {
                $this->saveProductAttribute($attribute, $product, 'volume');
            }
        }
    }

    protected function processUnderOrder($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::UNDER_ORDER) {
                $this->saveProductAttribute($attribute, $product, 'under_order');
            }
        }
    }

    protected function processCountryManufacture($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::COUNTRY_OF_MANUFACTURER) {
                $this->saveProductAttribute($attribute, $product, 'country_manufacture');
            }
        }
    }

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
        // Сохраняем/обновляем атрибут, не перетирая ru/en для name
        $productAttribute = Attribute::firstOrCreate(
            ['external_id' => $attribute->id],
            [
                'field_name' => $name,
                'name' => [
                    'uk' => $attribute->name,
                    'ru' => $attribute->name,
                    'en' => $attribute->name,
                ],
            ]
        );
        try {
            $names = method_exists($productAttribute, 'getTranslations')
                ? ($productAttribute->getTranslations('name') ?? [])
                : ($productAttribute->name ?? []);
            if (! is_array($names)) {
                $names = [];
            }
            $names['uk'] = $attribute->name;
            $productAttribute->update(['name' => $names, 'field_name' => $name]);
        } catch (\Throwable $e) {
            $productAttribute->update(['name' => ['uk' => $attribute->name], 'field_name' => $name]);
        }

        $existingPivot = $product->attributes()->where('attribute_id', $productAttribute->id)->first();

        if ($existingPivot) {
            $currentVal = $existingPivot->pivot->value;
            if (! is_array($currentVal)) {
                $currentVal = ['uk' => (string) $currentVal];
            }
            $currentVal['uk'] = is_object($attribute->value) ? ($attribute->value?->name ?? '') : $attribute->value;
            $product->attributes()->updateExistingPivot($productAttribute->id, ['value' => $currentVal]);
        } elseif (is_object($attribute->value)) {
            $product->attributes()->attach($productAttribute->id, [
                'value' => ['uk' => $attribute->value?->name],
            ]);
        } else {
            $product->attributes()->attach($productAttribute->id, [
                'value' => ['uk' => $attribute->value],
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
                        'file_name' => $mediaItem->file_name,
                    ]);
                }
            }

            // Обновляем медиа файл, чтобы обновить generated_conversions
            $mediaItem->refresh();

            Log::info('Конверсии сгенерированы для изображения', [
                'product_id' => $product->id,
                'media_id' => $mediaItem->id,
                'file_name' => $mediaItem->file_name,
                'generated_conversions' => $mediaItem->getGeneratedConversions()->keys()->toArray(),
            ]);

        } catch (\Exception $e) {
            Log::error('Ошибка генерации конверсий', [
                'product_id' => $product->id,
                'media_id' => $mediaItem->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
