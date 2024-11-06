<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        HasTranslations;

    protected $translatable = ['title', 'read_time', 'description'];

    protected $casts = [
        'read_time' => 'json',
        'title' => 'json',
        'description' => 'json',
    ];

      public function registerMediaConversions(?Media $media = null): void
      {
          $this
              ->addMediaConversion('preview')
              ->width(310)
              ->height(310)
              ->format('png')
              ->nonQueued();
      }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class);
    }
}
