<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessProductImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(): void
    {
        $product = $this->product;
        
        // Проверяем конверсии для существующих изображений
        $this->checkExistingImagesConversions($product);
        
        $username = config('app.my_store.username');
        $password = config('app.my_store.password');
        $encodedCredentials = base64_encode("{$username}:{$password}");
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
                        $this->handleImageDownload($product, $downloadUrl, $encodedCredentials, $image['filename']);
                    }
                }
            } else {
                Log::warning("No images found for product ID {$prodExternalId}.");
            }
        } else {
            Log::error("Failed to get images for product ID {$prodExternalId}. Status: {$response->status()}");
        }

        $this->syncProductStock($product);
    }

    protected function handleImageDownload($product, $downloadUrl, $encodedCredentials, $filename): void
    {
        $existingMediaItem = $product->getMedia('images')->first(function ($media) use ($downloadUrl) {
            return $media->getCustomProperty('download_url') === $downloadUrl;
        });

        if ($existingMediaItem) {
            Log::info("Image from '{$downloadUrl}' already exists for product ID {$product->external_id}. Checking conversions...");
            
            // Проверяем конверсии для существующего изображения
            $this->ensureConversionsExist($existingMediaItem);
            return;
        }

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

                $mediaItem->setCustomProperty('download_url', $downloadUrl);
                $mediaItem->save();

                // Проверяем и создаем конверсии если их нет
                $this->ensureConversionsExist($mediaItem);

                Log::info("Image '{$filename}' added for product ID {$product->external_id}.");
            } catch (\Exception $e) {
                Log::error("Failed to save image '{$filename}' for product ID {$product->external_id}. Error: {$e->getMessage()}");
            }
        } else {
            Log::error("Failed to download image from {$downloadUrl}. Status: {$imageResponse->status()}");
        }
    }

    protected function syncProductStock(): void
    {
        /** @var \App\Models\Product $product */
        $product = $this->product;

        $username = config('app.my_store.username');
        $password = config('app.my_store.password');
        $encodedCredentials = base64_encode("{$username}:{$password}");

        $jsonUrl = "https://api.moysklad.ru/api/remap/1.2/entity/assortment?filter=code~{$product->code}";

        $response = Http::withHeaders([
            'Authorization'   => 'Basic ' . $encodedCredentials,
            'Accept-Encoding' => 'gzip',
        ])->get($jsonUrl);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['rows'][0]['stock'])) {
                $product->update(['stock' => $data['rows'][0]['stock']]);
            } else {
                $product->update(['stock' => 0]);
            }
        }
    }

    /**
     * Проверяет конверсии для всех существующих изображений продукта
     */
    protected function checkExistingImagesConversions($product): void
    {
        $existingImages = $product->getMedia('images');
        
        if ($existingImages->isNotEmpty()) {
            Log::info("Проверяем конверсии для {$existingImages->count()} существующих изображений продукта {$product->external_id}");
            
            foreach ($existingImages as $mediaItem) {
                $this->ensureConversionsExist($mediaItem);
            }
        }
    }

    /**
     * Проверяет и создает конверсии для медиа файла если их нет
     */
    protected function ensureConversionsExist($mediaItem): void
    {
        try {
            // Получаем конфигурацию конверсий
            $conversions = \App\Models\MediaConversions::getConversionsConfig();
            
            foreach ($conversions as $conversionName => $config) {
                // Проверяем, существует ли конверсия
                if (!$mediaItem->hasGeneratedConversion($conversionName)) {
                    Log::info("Создаем отсутствующую конверсию {$conversionName} для {$mediaItem->file_name}");
                    
                    // Создаем конверсию через модель продукта
                    $product = $mediaItem->model;
                    if ($product) {
                        $conversion = $product->addMediaConversion($conversionName);
                        
                        if ($config['width'] && $config['height']) {
                            $conversion->width($config['width'])->height($config['height']);
                        }
                        
                        if ($config['quality']) {
                            $conversion->quality($config['quality']);
                        }
                        
                        if (isset($config['sharpen'])) {
                            $conversion->sharpen($config['sharpen']);
                        }
                        
                        if ($config['format']) {
                            $conversion->format($config['format']);
                        }
                        
                        if (isset($config['fit'])) {
                            $conversion->fit(\Spatie\Image\Enums\Fit::Contain);
                        }
                        
                        $conversion->optimize();
                        
                        foreach ($config['collections'] as $collection) {
                            $conversion->performOnCollections($collection);
                        }
                        
                        $conversion->nonQueued();
                        
                        // Выполняем конверсию
                        $conversion->perform();
                        
                        Log::info("✅ Создана конверсия {$conversionName} для {$mediaItem->file_name}");
                    }
                } else {
                    Log::info("Конверсия {$conversionName} уже существует для {$mediaItem->file_name}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Ошибка при создании конверсий для {$mediaItem->file_name}: " . $e->getMessage());
        }
    }
}
