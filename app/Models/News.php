<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
//use PhpParser\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia;

    protected $translatable = ['title', 'read_time', 'description', 'slug'];

    protected $casts = [
        'read_time' => 'json',
        'title' => 'json',
        'slug' => 'json',
        'description' => 'json',
    ];

    protected static function booted(): void
    {
        static::saving(function (News $news) {
            if (! $news->isDirty('title')) {
                return;
            }
            $news->setTranslations(
                'slug',
                array_merge(
                    $news->getTranslations('slug'),
                    $news->generateSlugsFromTitle()
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

    public function getSlugEnAttribute(): ?string
    {
        $slug = $this->getTranslation('slug', 'en');
        if ($slug !== null && $slug !== '') {
            return $slug;
        }
        $all = $this->getTranslations('slug');
        $first = (string) reset($all);
        if ($first !== '') {
            return $first;
        }
        $generated = $this->generateSlugsFromTitle();
        if ($generated !== []) {
            $this->setTranslations('slug', array_merge($this->getTranslations('slug'), $generated));
            $this->saveQuietly();

            return (string) reset($generated);
        }

        return null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview_webp')
            ->width(683)
            ->height(201)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('preview')
            ->width(683)
            ->height(201)
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class);
    }

    public function plainDescription(int $limit = 0): string
    {
        $html = (string) $this->description;
        $html = preg_replace('/<\/?(p|div|br|li|h[1-6]|blockquote|table|tr|td|th)[^>]*>/i', ' ', $html) ?? $html;
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\xc2\xa0", ' ', $text);
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
        $text = trim($text);

        return $limit > 0 ? Str::limit($text, $limit) : $text;
    }
}
