<?php

namespace App\Services;

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
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\Products\Product as ApiProduct;
use MoySklad\MoySklad;

class ProductService
{
    /**
     * @throws \Exception
     */
    public function processProducts(): void
    {
        $myStore = MoySklad::getInstance(config('app.my_store.username'), config('app.my_store.password'));

        $list = ApiProduct::query($myStore, QuerySpecs::create([
            'offset'     => 0,
            'maxResults' => 50,
        ]))->getList();

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
    }

    /**
     * @throws \Exception
     */
    public function processImages(): void
    {
        $products = Product::all()->filter(function ($product) {
            return $product->getMedia('images')->isEmpty();
        });

        $this->syncImages($products);
    }

    /**
     * @param $item
     */
    protected function processProduct($item): void
    {
        $parseData = $item->jsonSerialize();

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

        $this->processPrices($parseData->salePrices, $product);

       if (property_exists($parseData, 'attributes')) {
           $attributes = $parseData?->attributes?->attrs;
           $this->processCategories($attributes, $product);
           $this->updateName($attributes, $product);
//           $this->processExpenseCategory($attributes, $product);
           $this->processBrand($attributes, $product);
           $this->processApplication($attributes, $product);
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
                ['price' => $priceData['price']]
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
                $categoryIds = $this->getCategoryIds($attribute->value->name);
                $product->update(['category_id' => end($categoryIds)]);
            }
        }
    }

    /**
     * @param string $categoryPath
     *
     * @return array
     * @throws \JsonException
     */
    protected function getCategoryIds(string $categoryPath): array
    {
        $parts = array_map('trim', explode('>', $categoryPath));
        $parentId = null;
        $categoryIds = [];

        foreach ($parts as $part) {

            $category = Category::query()->whereJsonContains('name->uk', $part)->first();

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

            $parentId = $category->id;
            $categoryIds[] = $category->id;
        }

        return $categoryIds;
    }

    protected function updateName($attributes, $product): void
    {
        foreach ($attributes as $attribute) {

            if ($attribute->id === ProductAttributeEnum::NAME) {
                $this->saveProductAttribute($attribute, $product, 'name');
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
    protected function updateRoomTemperature($attributes, $product): void
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
                        if ($downloadUrl) {
                            $this->handleImageDownload($product, $downloadUrl, $encodedCredentials);
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
                $product->addMediaFromStream($fileContent)
                    ->usingFileName($uniqueFilename)
                    ->toMediaCollection('images');
            } catch (\Exception $e) {
                Log::error("Failed to save image for product ID {$product->external_id}. Error: {$e->getMessage()}");
            }
        } else {
            Log::error("Failed to download image from {$downloadUrl}. Status: {$imageResponse->status()}");
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
            $product->attributes()->updateExistingPivot($productAttribute->id, [
                'value' => json_encode([
                    'uk' => $attribute->value ?? $attribute->value?->name,
                    'ru' => $attribute->value ?? $attribute->value?->name,
                    'en' => $attribute->value ?? $attribute->value?->name,
                ], JSON_THROW_ON_ERROR)
            ]);
        } else {
            // Если связи нет, добавляем новую
            $product->attributes()->attach($productAttribute->id, [
                'value' => json_encode([
                    'uk' => $attribute->value ?? $attribute->value?->name,
                    'ru' => $attribute->value ?? $attribute->value?->name,
                    'en' => $attribute->value ?? $attribute->value?->name,
                ], JSON_THROW_ON_ERROR)
            ]);
        }
    }
}
