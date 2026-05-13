<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class BlogPost extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia;

    public array $translatable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
    ];

    protected $fillable = [
        'blog_author_id',
        'blog_category_id',
        'product_catalog_category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'read_time_minutes',
        'is_featured',
        'is_active',
        'comments_enabled',
        'faq_items',
        'published_at',
    ];

    protected $casts = [
        'title' => 'json',
        'slug' => 'json',
        'excerpt' => 'json',
        'body' => 'json',
        'meta_title' => 'json',
        'meta_description' => 'json',
        'read_time_minutes' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'comments_enabled' => 'boolean',
        'faq_items' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post) {
            if (! $post->isDirty('title')) {
                return;
            }
            $post->setTranslations(
                'slug',
                array_merge(
                    $post->getTranslations('slug'),
                    $post->generateSlugsFromTitle()
                )
            );
        });
    }

    protected function generateSlugsFromTitle(): array
    {
        $slugs = [];
        $titles = $this->getTranslations('title');
        $locales = $this->getTranslatableLocales();

        foreach ($locales as $locale) {
            if (! empty($titles[$locale])) {
                $slugs[$locale] = Str::slug($titles[$locale]);
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Широкий банер лише для OG / schema.org (рекомендовано ~1200×630).
        $this->addMediaConversion('cover_og')
            ->fit(Fit::Crop, 1200, 630)
            ->format('webp')
            ->nonQueued();

        // Fallback (квадрат), якщо інших конверсій ще немає.
        $this->addMediaConversion('cover_lg')
            ->fit(Fit::Crop, 920, 920)
            ->format('webp')
            ->nonQueued();

        // Featured на головній блогу + hero статті: 630×355 @2×.
        $this->addMediaConversion('cover_article')
            ->fit(Fit::Crop, 1260, 710)
            ->format('webp')
            ->nonQueued();

        // Hero сторінки статті (десктоп): 1132×334.
        $this->addMediaConversion('cover_hero')
            ->fit(Fit::Crop, 1132, 334)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('cover_sm')
            ->fit(Fit::Crop, 800, 480)
            ->format('webp')
            ->nonQueued();
    }

    public function coverMedia(): ?Media
    {
        return $this->getFirstMedia('cover');
    }

    public function hasCover(): bool
    {
        return $this->coverMedia() !== null;
    }

    /**
     * WebP 1132×334: великий банер у шапці сторінки статті.
     */
    public function coverUrlHero(): string
    {
        $media = $this->coverMedia();

        return $media ? $media->getAvailableFullUrl(['cover_hero', 'cover_article', 'cover_og', 'cover_lg']) : '';
    }

    /**
     * WebP 630×355 @2×: головний блог (featured) і fallback hero.
     */
    public function coverUrlArticle(): string
    {
        $media = $this->coverMedia();

        return $media ? $media->getAvailableFullUrl(['cover_article', 'cover_og', 'cover_lg']) : '';
    }

    /** @deprecated Використовуйте coverUrlArticle() */
    public function coverUrlLarge(): string
    {
        return $this->coverUrlArticle();
    }

    /**
     * Менший WebP для карток і мобільного hero (800×480).
     */
    public function coverUrlSmall(): string
    {
        $media = $this->coverMedia();

        return $media ? $media->getAvailableFullUrl(['cover_sm', 'cover_article', 'cover_lg']) : '';
    }

    /**
     * Широкий банер для og:image та JSON-LD (1200×630).
     */
    public function coverUrlOg(): string
    {
        $media = $this->coverMedia();

        return $media ? $media->getAvailableFullUrl(['cover_og', 'cover_lg']) : '';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(BlogAuthor::class, 'blog_author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function productCatalogCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'product_catalog_category_id');
    }

    /**
     * Товари для блоку на сторінці статті (порядок — поле sort_order у pivot).
     */
    public function attachedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'blog_post_product')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id');
    }

    public function approvedRootComments(): HasMany
    {
        return $this->comments()->approved()->root()->orderBy('created_at');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function url(): string
    {
        $slug = $this->getTranslation('slug', app()->getLocale())
            ?: (string) reset($this->getTranslations('slug'));

        return route('blog.show', ['blog_post' => $slug]);
    }

    /**
     * Для майбутніх товарних вставок у текст (shortcodes / окремий JSON) — поки без БД-поля.
     */
    public function bodyHtml(): string
    {
        return (string) $this->getTranslation('body', app()->getLocale());
    }

    public function isPublic(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Дата для карток і розмітки: поле «Опубліковано», якщо задано, інакше created_at.
     */
    public function publicDisplayDate(): \Illuminate\Support\Carbon
    {
        return $this->published_at ?? $this->created_at;
    }

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
}
