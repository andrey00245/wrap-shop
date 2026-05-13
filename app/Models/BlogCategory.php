<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class BlogCategory extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['name', 'slug'];

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'json',
        'slug' => 'json',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (BlogCategory $category) {
            if (! $category->isDirty('name')) {
                return;
            }
            $category->setTranslations(
                'slug',
                array_merge(
                    $category->getTranslations('slug'),
                    $category->generateSlugsFromName()
                )
            );
        });
    }

    protected function generateSlugsFromName(): array
    {
        $slugs = [];
        $names = $this->getTranslations('name');
        $locales = $this->getTranslatableLocales();

        foreach ($locales as $locale) {
            if (! empty($names[$locale])) {
                $slugs[$locale] = Str::slug($names[$locale]);
            }
        }
        $slugs = array_filter($slugs);
        $fallback = (string) reset($slugs);
        foreach ($locales as $locale) {
            if (empty($slugs[$locale]) && $fallback !== '') {
                $slugs[$locale] = $fallback;
            }
        }

        return $slugs;
    }

    protected function getTranslatableLocales(): array
    {
        return config('tab-translatable.locales', ['uk', 'ru', 'en']);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'blog_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithPublishedPosts(Builder $query): Builder
    {
        return $query->whereHas('posts', fn (Builder $q) => $q->published());
    }
}
