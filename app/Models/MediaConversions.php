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
                'width' => 482,
                'height' => 482,
                'quality' => 100,
                'sharpen' => 10,
                'format' => 'jpg',
                'collections' => ['images'],
                'fit' => 'contain'
            ],
            'preview_webp' => [
                'width' => 482,
                'height' => 482,
                'quality' => 100,
                'sharpen' => 10,
                'format' => 'webp',
                'collections' => ['images'],
                'fit' => 'contain'
            ],
            'gallery' => [
                'width' => 800,
                'height' => 800,
                'quality' => 100,
                'sharpen' => 0,
                'format' => 'webp',
                'collections' => ['images']
            ]
        ];
    }
}
