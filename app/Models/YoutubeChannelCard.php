<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class YoutubeChannelCard extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $with = ['media'];

    protected $fillable = [
        'title',
        'button_text',
        'button_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected static function booted(): void
    {
        static::saved(static function () {
            HomeIndexCache::flush();
        });

        static::deleted(static function () {
            HomeIndexCache::flush();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Crop, 636, 279)
            ->quality(95)
            ->nonQueued();

        $this
            ->addMediaConversion('preview_webp')
            ->fit(Fit::Crop, 636, 279)
            ->format('webp')
            ->quality(92)
            ->nonQueued();
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

            return $media->getUrl();
        }

        // Fallback for legacy records that still use DB image path.
        if (! empty($this->getAttribute('image'))) {
            return asset('storage/'.ltrim((string) $this->getAttribute('image'), '/'));
        }

        return '';
    }
}
