<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class HomeBrand extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $with = ['media'];

    protected $fillable = [
        'name',
        'sort_order',
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

    public function getLogoUrl(): string
    {
        return $this->getFirstMediaUrl('main') ?: '';
    }
}
