<?php

namespace App\Services;

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
                dd($item, $e->getMessage(),$e->getFile(),$e->getLine());
                DB::rollBack();
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

            $product = Product::query()->updateOrCreate(
                ['external_id' => $parseData->id],
                [
                    'description'   => $parseData->description ?? null,
                    'name'          => $parseData->name ?? null,
                    'external_code' => $parseData->externalCode,
                    'code'          => $parseData->code,
                    'article'       => $parseData->article ?? null,
                ]
            );
                $this->processPrices($parseData->salePrices, $product);
                if (isset($parseData->attributes)){
                    $this->processCategories($parseData->attributes->attrs, $product);
                }
    }

    /**
     * @param $salePrices
     * @param $product
     */
    protected function processPrices($salePrices, $product): void
    {
        $priceTypes = [];
        foreach ($salePrices as $salePrice) {
            $priceTypes[] = [
                'external_id'   => $salePrice->priceType->id,
                'name'          => $salePrice->priceType->name,
                'external_code' => $salePrice->priceType->externalCode,
                'price'         => (int)$salePrice->value,
                'product_id'    => $product->id,
            ];
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
                    'price_type_id' => $priceType->id,
                    'product_id'    => $product->id,
                ],
                ['price' => $priceData['price']]
            );
        }
    }

    /**
     * @param $attributes
     * @param $product
     */
    protected function processCategories($attributes, $product): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute->id === ProductAttributeEnum::SITE_CATEGORY) {
                $parts = explode('>', $attribute->value->name);
                $parts = array_map('trim', $parts);

                $parentId = null;
                $lastCategory = null;

                foreach ($parts as $part) {
                    $category = Category::query()->firstOrCreate(
                        ['name' => $part, 'parent_id' => $parentId]
                    );

                    $parentId = $category->id;
                    $lastCategory = $category;
                }

                $product->categories()->sync([$lastCategory->id], false);
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
}
