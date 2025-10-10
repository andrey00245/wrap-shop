<?php

namespace App\Models;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaConversions
{
    public static function registerConversions(Media $media = null): void
    {
        // Этот метод будет вызываться из модели Product
        // Конверсии регистрируются через addMediaConversion в модели
    }
    
    public static function getConversionsConfig(): array
    {
        return [
            'preview' => [
                'width' => 310,
                'height' => 310,
                'quality' => 85,
                'sharpen' => 10,
                'format' => null,
                'collections' => ['images']
            ],
            'thumbnail' => [
                'width' => 150,
                'height' => 150,
                'quality' => 80,
                'sharpen' => 5,
                'format' => null,
                'collections' => ['images']
            ],
            'gallery' => [
                'width' => 800,
                'height' => 800,
                'quality' => 90,
                'sharpen' => 10,
                'format' => null,
                'collections' => ['images']
            ],
            'webp' => [
                'width' => null,
                'height' => null,
                'quality' => 85,
                'sharpen' => 0,
                'format' => 'webp',
                'collections' => ['images']
            ],
            'preview_webp' => [
                'width' => 310,
                'height' => 310,
                'quality' => 85,
                'sharpen' => 10,
                'format' => 'webp',
                'collections' => ['images']
            ]
        ];
    }
}
