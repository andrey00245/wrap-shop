<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
//use PhpParser\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        HasTranslations;

    protected $translatable = ['title', 'read_time', 'description', 'slug'];

    protected $casts = [
        'read_time' => 'json',
        'title' => 'json',
        'slug' => 'json',
        'description' => 'json',
    ];

  protected static function booted()
  {
    static::saving(function ($news) {
      static::generateSlug($news);
    });

    static::creating(function ($news) {
      static::generateSlug($news);
    });
  }

  public static function generateSlug(News $model){
    $model->slug = [
      'en' => Str::slug($model->getTranslation('title', 'en')),
      'uk' => Str::slug($model->getTranslation('title', 'uk')),
      'ru' => Str::slug($model->getTranslation('title', 'ru'))
    ];
  }

  public function getSlugEnAttribute()
  {
    return $this->getTranslation('slug', 'en');
  }

  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }

      public function registerMediaConversions(?Media $media = null): void
      {
          $this
              ->addMediaConversion('preview')
              ->width(683)
              ->height(201)
              ->format('jpg')
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
