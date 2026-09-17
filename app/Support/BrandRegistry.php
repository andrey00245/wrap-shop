<?php

namespace App\Support;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BrandRegistry
{
    public const CACHE_KEY = 'brand_registry.distinct_brands';

    public const CACHE_TTL_SECONDS = 3600;

    /**
     * Унікальні бренди з атрибута products (field_name = brand).
     *
     * @return Collection<int, array{key: string, name: string}>
     */
    public static function distinctBrands(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            $attributeId = Attribute::query()
                ->where('field_name', 'brand')
                ->value('id');

            if (! $attributeId) {
                return collect();
            }

            $grouped = [];

            ProductAttribute::query()
                ->where('attribute_id', $attributeId)
                ->select(['id', 'value'])
                ->orderBy('id')
                ->chunkById(500, function ($rows) use (&$grouped): void {
                    foreach ($rows as $row) {
                        $key = Product::normalizePivotValueForCatalogFacetKey($row->value);
                        if ($key === null || $key === '') {
                            continue;
                        }

                        $grouped[$key][] = $row->value;
                    }
                });

            return collect($grouped)
                ->map(function (array $rawValues, string $key): array {
                    $labels = collect($rawValues)
                        ->flatMap(function (mixed $raw): array {
                            $labels = [];
                            foreach (['uk', 'ru', 'en'] as $locale) {
                                $label = Product::extractPivotValueForDisplay($raw, $locale);
                                if ($label !== null && $label !== '') {
                                    $labels[] = $label;
                                }
                            }

                            return $labels;
                        })
                        ->unique()
                        ->values();

                    return [
                        'key' => $key,
                        'name' => Product::pickCanonicalAttributeDisplayValue($labels),
                    ];
                })
                ->filter(fn (array $brand) => $brand['name'] !== '')
                ->sortBy(fn (array $brand) => mb_strtolower($brand['name']))
                ->values();
        });
    }

    /**
     * @param  list<string|null>  $excludeKeys
     * @return array<string, string>
     */
    public static function optionsForNova(array $excludeKeys = []): array
    {
        $exclude = collect($excludeKeys)
            ->filter()
            ->map(fn ($key) => mb_strtolower(trim((string) $key)))
            ->unique()
            ->all();

        return static::distinctBrands()
            ->reject(fn (array $brand) => in_array($brand['key'], $exclude, true))
            ->mapWithKeys(fn (array $brand) => [$brand['key'] => $brand['name']])
            ->all();
    }

    public static function displayNameForKey(?string $brandKey): ?string
    {
        if ($brandKey === null || trim($brandKey) === '') {
            return null;
        }

        $key = mb_strtolower(trim($brandKey));

        return static::distinctBrands()
            ->firstWhere('key', $key)['name'] ?? null;
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
