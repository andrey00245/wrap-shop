<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class BlogAuthor extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia;

    public array $translatable = ['name', 'role', 'bio'];

    protected $fillable = [
        'slug',
        'name',
        'role',
        'bio',
        'avatar_url',
        'socials',
        'is_active',
    ];

    protected $casts = [
        'name' => 'json',
        'role' => 'json',
        'bio' => 'json',
        'socials' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (BlogAuthor $author) {
            $nameForSlug = $author->resolveNameForSlug();
            if ($nameForSlug !== null && $nameForSlug !== '') {
                $author->slug = $author->freshUniqueSlug($nameForSlug);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->fit(Fit::Crop, 192, 192)
            ->format('webp')
            ->nonQueued();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'blog_author_id');
    }

    public function publishedPosts(): HasMany
    {
        return $this->posts()->published();
    }

    public function avatarUrl(): string
    {
        $media = $this->getFirstMedia('avatar');
        if ($media !== null) {
            return $media->getUrl('avatar');
        }

        if (! empty($this->avatar_url)) {
            return $this->avatar_url;
        }

        return asset('assets/img/logo.png');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function resolveNameForSlug(): ?string
    {
        foreach (['uk', 'en', 'ru'] as $locale) {
            $n = $this->getTranslation('name', $locale);
            if (is_string($n) && trim($n) !== '') {
                return trim($n);
            }
        }
        foreach ($this->getTranslations('name') ?? [] as $n) {
            if (is_string($n) && trim($n) !== '') {
                return trim($n);
            }
        }

        return null;
    }

    protected function freshUniqueSlug(string $nameForSlug): string
    {
        $base = Str::slug($nameForSlug);
        if ($base === '') {
            $base = 'author';
        }

        $slug = $base;
        $n = 2;
        while (static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn ($q) => $q->where('id', '!=', $this->id))
            ->exists()
        ) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }
}
