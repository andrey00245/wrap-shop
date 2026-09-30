<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class HomePage extends Model
{
    use HasTranslations;

    public array $translatable = ['content', 'seo_text'];

    protected $fillable = [
        'content',
        'seo_text',
    ];

    protected $casts = [
        'content' => 'json',
        'seo_text' => 'json',
    ];

    protected static function booted(): void
    {
        static::saved(static function () {
            static::flushHomeCache();
        });

        static::deleted(static function () {
            static::flushHomeCache();
        });
    }

    public static function flushHomeCache(): void
    {
        foreach (['uk', 'ru', 'en'] as $locale) {
            Cache::forget('home.index.v2.'.$locale);
        }
    }
}
