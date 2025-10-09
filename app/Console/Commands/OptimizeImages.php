<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OptimizeImages extends Command
{
    protected $signature = 'optimize:images';
    protected $description = 'Optimize all product images for better performance';

    public function handle()
    {
        $this->info('🖼️ Starting image optimization...');

        $products = Product::whereHas('media')->with('media')->get();
        $totalImages = 0;
        $optimizedImages = 0;

        foreach ($products as $product) {
            $images = $product->getMedia('images');
            $totalImages += $images->count();

            foreach ($images as $image) {
                try {
                    // Generate WebP version if not exists
                    if (!$image->hasGeneratedConversion('webp')) {
                        $image->addMediaConversion('webp')
                            ->format('webp')
                            ->quality(85)
                            ->performOnCollections('images');
                    }

                    // Generate optimized preview
                    if (!$image->hasGeneratedConversion('preview_optimized')) {
                        $image->addMediaConversion('preview_optimized')
                            ->width(310)
                            ->height(310)
                            ->quality(80)
                            ->performOnCollections('images');
                    }

                    $optimizedImages++;
                } catch (\Exception $e) {
                    $this->warn("Failed to optimize image {$image->id}: {$e->getMessage()}");
                }
            }
        }

        $this->info("✅ Optimized {$optimizedImages} out of {$totalImages} images");
        $this->info('🎉 Image optimization completed!');
    }
}
