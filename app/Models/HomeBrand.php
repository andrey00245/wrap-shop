<?php

namespace App\Models;

use App\Support\BrandRegistry;
use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HomeBrand extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $with = ['media'];

    protected $fillable = [
        'brand_key',
        'name',
        'sort_order',
    ];

    /** @var array<string, string>|null */
    protected static ?array $logoUrlByKey = null;

    protected static function booted(): void
    {
        static::saving(static function (HomeBrand $brand): void {
            if (filled($brand->brand_key)) {
                $brand->brand_key = mb_strtolower(trim((string) $brand->brand_key));
                $brand->name = BrandRegistry::displayNameForKey($brand->brand_key) ?? $brand->name;
            }
        });

        static::saved(static function (): void {
            static::flushLogoCache();
            HomeIndexCache::flush();
        });

        static::deleted(static function (): void {
            static::flushLogoCache();
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

    public static function flushLogoCache(): void
    {
        static::$logoUrlByKey = null;
    }

    /**
     * @return array<string, string>
     */
    public static function logoUrlByKey(): array
    {
        if (static::$logoUrlByKey !== null) {
            return static::$logoUrlByKey;
        }

        static::$logoUrlByKey = static::query()
            ->whereNotNull('brand_key')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (self $brand) => $brand->getLogoUrl() !== '')
            ->mapWithKeys(fn (self $brand) => [(string) $brand->brand_key => $brand->getLogoUrl()])
            ->all();

        return static::$logoUrlByKey;
    }

    public static function resolveLogoUrlForAttributeValue(mixed $rawValue): string
    {
        $key = Product::normalizePivotValueForCatalogFacetKey($rawValue);
        if ($key === null || $key === '') {
            return '';
        }

        return static::logoUrlByKey()[$key] ?? '';
    }

    public static function resolveForAttributeValue(mixed $rawValue): ?self
    {
        $key = Product::normalizePivotValueForCatalogFacetKey($rawValue);
        if ($key === null || $key === '') {
            return null;
        }

        return static::query()->where('brand_key', $key)->first();
    }
}
