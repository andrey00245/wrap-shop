<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia;

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
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

    protected $translatable = ['name', 'slug', 'meta_title', 'meta_description', 'meta_keywords', 'h1', 'content', 'seo_text'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'h1',
        'content',
        'seo_text',
        'faq_items',
    ];

    protected $casts = [
        'name' => 'json',
        'slug' => 'json',
        'meta_title' => 'json',
        'meta_description' => 'json',
        'meta_keywords' => 'json',
        'h1' => 'json',
        'content' => 'json',
        'seo_text' => 'json',
        'faq_items' => 'array',
    ];

    public function faqForSchema(): array
    {
        $items = $this->faq_items;
        if (! is_array($items)) {
            return [];
        }

        $out = [];
        foreach ($items as $row) {
            if (! is_array($row)) {
                continue;
            }
            $q = trim((string) ($row['question'] ?? ''));
            $a = trim((string) ($row['answer'] ?? ''));
            if ($q !== '' && $a !== '') {
                $out[] = ['question' => $q, 'answer' => $a];
            }
        }

        return $out;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(310)
            ->height(310)
            ->nonQueued();

        $this
            ->addMediaConversion('preview_webp')
            ->width(310)
            ->height(310)
            ->format('webp')
            ->nonQueued();
    }

    public function getAllChildren()
    {
        $children = $this->children;
        $allChildren = collect($children);

        foreach ($children as $child) {
            $allChildren = $allChildren->merge($child->getAllChildren());
        }

        return $allChildren;
    }

    public function allDescendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->allDescendantIds());
        }

        return $ids;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }

    public function getSlugEnAttribute()
    {
        return $this->getTranslation('slug', 'en');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function faqs(): BelongsToMany
    {
        return $this->belongsToMany(Faq::class, 'category_faq')
            ->withPivot('sort_order')
            ->orderBy('pivot_sort_order')
            ->orderBy('faqs.order');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Сегменти URL каталогу від кореня до цієї категорії: plivki/kolorovi-plivky
     */
    public function catalogPath(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $chain = [];
        $cursor = $this;
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

        return implode('/', $segments);
    }

    public function getNameUkAttribute()
    {
        return $this->getTranslation('name', 'uk');
    }

    public function getImage(): string
    {
        return $this->getFirstMediaUrl('main');
    }

    public function getPreviewImage(): string
    {
        $media = $this->getFirstMedia('main');
        if ($media && $media->hasGeneratedConversion('preview_webp')) {
            return $media->getUrl('preview_webp');
        }

        return $this->getFirstMediaUrl('main');
    }

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    protected function generateSlugsFromName(): array
    {
        $slugs = [];
        $names = $this->getTranslations('name');

        foreach ($this->getTranslatableLocales() as $locale) {
            if (! empty($names[$locale])) {
                $slugs[$locale] = Str::slug($names[$locale]);
            }
        }

        return array_filter($slugs);
    }

    protected function getTranslatableLocales(): array
    {
        return config('tab-translatable.locales', ['uk', 'ru', 'en']);
    }
}
