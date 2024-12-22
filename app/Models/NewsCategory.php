<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class NewsCategory extends Model
{
    use HasFactory,
        HasTranslations;

    protected $translatable = ['name', 'slug'];

  protected static function booted()
  {
    static::saving(function ($category) {
        static::generateSlug($category);
    });

    static::creating(function ($category) {
      static::generateSlug($category);
    });
  }

  public static function generateSlug(NewsCategory $model){
    $model->slug = [
      'en' => Str::slug($model->getTranslation('name', 'en')),
      'uk' => Str::slug($model->getTranslation('name', 'uk')),
      'ru' => Str::slug($model->getTranslation('name', 'ru'))
    ];
  }

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
        'slug',
    ];

    protected $casts = [
        'name' => 'json',
        'slug' => 'json',
    ];

    public function getNameUkAttribute()
    {
        return $this->getTranslation('name', 'uk');
    }

  public function getSlugEnAttribute()
  {
    return $this->getTranslation('slug', 'en');
  }

    public function news(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class,'category_id');
    }
}
