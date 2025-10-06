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
use App\Http\Enums\ProductAttributeEnum;
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

    protected function handleImageDownload($product, $downloadUrl, $encodedCredentials): void
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
                $mediaItem = $product->addMediaFromStream($fileContent)
                    ->usingFileName($uniqueFilename)
                    ->toMediaCollection('images');

                $mediaItem->getConversions();

            } catch (\Exception $e) {
                Log::error("Failed to save image for product ID {$product->external_id}. Error: {$e->getMessage()}");
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
            'product_id' => $productData['id'] ?? 'unknown'
        ]);

        // Проверяем атрибут "Сайт" как в оригинальном коде
        if (isset($productData['attributes'])) {
            $hasSiteAttribute = false;
            foreach ($productData['attributes'] as $attribute) {
                if (isset($attribute['id']) && $attribute['id'] === '10726') { // ProductAttributeEnum::SITE
                    if (isset($attribute['value']['name']) && $attribute['value']['name'] === 'так') {
                        $hasSiteAttribute = true;
                        break;
                    }
                }
            }
            
            if (!$hasSiteAttribute) {
                Log::info('Товар не имеет атрибута "Сайт" со значением "так" - пропускаем', [
                    'product_id' => $productData['id'] ?? 'unknown'
                ]);
                return;
            }
        } else {
            Log::info('Товар не имеет атрибутов - пропускаем', [
                'product_id' => $productData['id'] ?? 'unknown'
            ]);
            return;
        }

        // Создаем товар
        $product = \App\Models\Product::updateOrCreate(
            ['external_id' => $productData['id']],
            [
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
            ]
        );

        $product->slug = [
            'en' => \Illuminate\Support\Str::slug($product->getTranslation('name', 'en')),
            'uk' => \Illuminate\Support\Str::slug($product->getTranslation('name', 'uk')),
            'ru' => \Illuminate\Support\Str::slug($product->getTranslation('name', 'ru'))
        ];

        $product->save();

        // Обрабатываем цены
        if (isset($productData['salePrices'])) {
            $this->processPrices($productData['salePrices'], $product);
        }

        Log::info('Товар обработан из вебхука', [
            'product_id' => $product->id,
            'external_id' => $product->external_id
        ]);
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

//           //10726
//           if ($parseData->code == 10617)
//           {
//               dd($parseData);
//           }else{
//               return;
//           }

           foreach ($attributes as $attribute) {
               if ($attribute->id === ProductAttributeEnum::SITE) {
                   if ($attribute->value->name === 'так'){
                       $product = Product::updateOrCreate(
                           ['external_id' => $parseData->id],
                           [
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
                           ]
                       );

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
        $priceTypes = array_map(function ($salePrice) use ($product) {
            return [
                'external_id'   => $salePrice->priceType->id,
                'name'          => $salePrice->priceType->name,
                'external_code' => $salePrice->priceType->externalCode,
                'price'         => (int) $salePrice->value,
                'product_id'    => $product->id,
            ];
        }, $salePrices);


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
                ]
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
}
