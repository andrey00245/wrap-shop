<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class SeoFilterPage extends Model
{
    use HasTranslations;

    public array $translatable = ['meta_title', 'meta_description', 'seo_text'];

    protected $fillable = [
        'category_id',
        'attribute_id',
        'filter_value',
        'slug',
        'meta_title',
        'meta_description',
        'seo_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (SeoFilterPage $page) {
            if (($page->slug === null || $page->slug === '') && $page->attribute_id && $page->filter_value) {
                $attr = $page->relationLoaded('attribute') ? $page->attribute : Attribute::find($page->attribute_id);
                if ($attr) {
                    $name = $attr->getTranslation('name', 'uk') ?: $attr->getTranslation('name', 'en') ?: $attr->field_name ?? 'filter';
                    $page->slug = self::generateSlug($name, $page->filter_value);
                }
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Повертає масив для selectedFilterValues: [ field_name => [ filter_value ] ].
     * Використовується для застосування одного фільтра з SEO-сторінки.
     */
    public function getSelectedFilterValues(): array
    {
        if (! $this->attribute_id || $this->filter_value === null || $this->filter_value === '') {
            return [];
        }
        $fieldName = $this->attribute->field_name ?? null;
        if (! $fieldName) {
            return [];
        }

        return [$fieldName => [$this->filter_value]];
    }

    /**
     * Генерує slug з назви атрибута та значення (напр. "Колір" + "Чорний" → kolir-chornyj).
     */
    public static function generateSlug(string $attributeNameOrSlug, string $value): string
    {
        $part1 = Str::slug($attributeNameOrSlug);
        $part2 = Str::slug($value);
        if ($part1 === '' && $part2 === '') {
            return Str::slug($value ?: $attributeNameOrSlug);
        }
        if ($part1 === '') {
            return $part2;
        }
        if ($part2 === '') {
            return $part1;
        }

        return $part1.'-'.$part2;
    }

    /**
     * Повертає шлях для URL (без локалі та /catalog/): напр. "plivki/kolir-chornyj".
     */
    public function getFullPath(string $locale = 'uk'): string
    {
        if (! $this->slug) {
            return '';
        }
        $category = $this->category;
        if (! $category) {
            return $this->slug;
        }
        $chain = [];
        $cursor = $category;
        while ($cursor) {
            $chain[] = $cursor;
            $cursor = $cursor->parent;
        }
        $chain = array_reverse($chain);
        $segments = [];
        foreach ($chain as $c) {
            $slug = $c->getTranslation('slug', $locale) ?: $c->getTranslation('slug', 'en');
            if ($slug !== null && $slug !== '') {
                $segments[] = $slug;
            }
        }
        $categoryPath = implode('/', $segments);

        return $categoryPath !== '' ? $categoryPath.'/'.$this->slug : $this->slug;
    }

    /**
     * Повертає повний URL сторінки на сайті (для перегляду в адмінці).
     */
    public function getFullUrl(string $locale = 'uk'): string
    {
        $path = $this->getFullPath($locale);
        if ($path === '') {
            return '';
        }

        $previous = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
        \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale($locale);
        $url = route('products.category', ['path' => $path]);
        \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale($previous);

        return $url;
    }

    /**
     * Для відображення в Nova (повне посилання на сторінку).
     */
    public function getLinkAttribute(): string
    {
        return $this->getFullUrl('uk') ?: '—';
    }
}
