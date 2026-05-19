<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class KitSection extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'lead'];

    protected $fillable = [
        'title',
        'lead',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'lead' => 'array',
        'is_active' => 'boolean',
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

    public static function defaultId(): int
    {
        return (int) static::query()->firstOrCreate(
            ['id' => 1],
            [
                'title' => ['uk' => '', 'ru' => '', 'en' => ''],
                'is_active' => true,
            ]
        )->id;
    }

    public function kits(): HasMany
    {
        return $this->hasMany(Kit::class)->orderBy('sort_order');
    }
}
