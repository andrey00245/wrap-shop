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

class GalleryImageDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $imageUrl;
    protected $product;

    public function __construct($imageUrl, Product $product)
    {
        $this->imageUrl = $imageUrl;
        $this->product = $product;
    }

    public function handle(): void
    {
        $imageUrl = $this->imageUrl;
        $product = $this->product;

        $imageResponse = Http::get($imageUrl);

        if ($imageResponse->successful()) {
            $fileContent = $imageResponse->body();
            $hash = md5($imageUrl);
            $uniqueFilename = $hash . '.png';
            $existingImage = $product->getMedia('banner_images')->firstWhere('file_name', $uniqueFilename);

            if ($existingImage) {
                dump('Skip');
                Log::info("Image with URL {$imageUrl} already exists for product ID {$product->external_id}. Skipping.");
            } else {
                try {
                    $mediaItem = $product->addMediaFromStream($fileContent)
                        ->usingFileName($uniqueFilename)
                        ->toMediaCollection('banner_images');

                    $mediaItem->getConversions();

                    Log::info("Successfully added image with URL {$imageUrl} to product ID {$product->external_id}.");
                } catch (\Exception $e) {
                    Log::error("Failed to save gallery image for product ID {$product->external_id}. Error: {$e->getMessage()}");
                }
            }
        } else {
            Log::error("Failed to download gallery image from {$imageUrl}. Status: {$imageResponse->status()}");
        }
    }
}
