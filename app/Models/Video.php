<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Video extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        HasTranslations;

    protected $translatable = ['title'];

    protected $casts = [
    'title' => 'json',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->format('jpg')
            ->width(320)
            ->height(180)
            ->performOnCollections('image');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class);
    }

    public function getUrl()
    {
        return asset('/storage/'. $this->video);
    }

    public function getImage(): string
    {
        return $this->getFirstMediaUrl('image');
    }
}
