<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Banner extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;

    protected $with = ['media'];

    protected $casts = [
        'categories' => 'array',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview_webp')
            ->width(748)
            ->height(257)
            ->quality(100)
            ->nonQueued();

        $this
            ->addMediaConversion('preview_webp')
            ->width(1960)
            ->height(674)
            ->format('webp')
            ->quality(85)
            ->nonQueued();

        /** Вебп для головного слайдера (~розмір у верстці) — менший за preview_webp, краще для LCP */
        $this
            ->addMediaConversion('hero')
            ->fit(Fit::Crop, 1312, 450)
            ->format('webp')
            ->quality(82)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }

    public function getImage(): string
    {
        return $this->getFirstMediaUrl('main');
    }

    public function getPreviewImage(): string
    {
        $media = $this->getFirstMedia('main');

        if ($media) {
            if ($media->hasGeneratedConversion('preview_webp')) {
                return $media->getUrl('preview_webp');
            }

            if ($media->hasGeneratedConversion('preview')) {
                return $media->getUrl('preview');
            }

            // Если нет конверсий, возвращаем оригинальный URL
            return $media->getUrl();
        }

        return '';
    }

    /**
     * Зображення для верхнього банера на головній (легший WebP, пріоритет LCP).
     * Після деплою: php artisan media-library:regenerate "App\Models\Banner" --only-missing --only=hero
     */
    public function getHeroImageUrl(): string
    {
        $media = $this->getFirstMedia('main');

        if (! $media) {
            return '';
        }

        if ($media->hasGeneratedConversion('hero')) {
            return $media->getUrl('hero');
        }

        if ($media->hasGeneratedConversion('preview_webp')) {
            return $media->getUrl('preview_webp');
        }

        if ($media->hasGeneratedConversion('preview')) {
            return $media->getUrl('preview');
        }

        return $media->getUrl();
    }
}
