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
            dump('Skip');
            Log::info("Image from '{$downloadUrl}' already exists for product ID {$product->external_id}. Skipping...");
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

                $mediaItem->getConversions();

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
}
