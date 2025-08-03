<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductBanner extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;

    protected $fillable = [
        'active',
        'position',
    ];

    protected $with = ['media'];

    protected $casts = [
        'categories' => 'array',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        if (!$media) {
            return;
        }

        if ($media->collection_name === 'vertical') {
            $this->addMediaConversion('preview_vertical')
//                ->width(316)
//                ->height(545)
                ->quality(100) // можно до 100, но будет весить больше
                ->nonQueued();
        }

        if ($media->collection_name === 'horizontal') {
            $this->addMediaConversion('preview_horizontal')
//                ->width(978)
//                ->height(188)
                ->quality(100)
                ->nonQueued();
        }
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('vertical')->singleFile();
        $this->addMediaCollection('horizontal')->singleFile();
    }

    public function getVerticalPreview(): string
    {
        return $this->getFirstMediaUrl('vertical', 'preview_vertical');
    }

    public function getHorizontalPreview(): string
    {
        return $this->getFirstMediaUrl('horizontal', 'preview_horizontal');
    }
}
