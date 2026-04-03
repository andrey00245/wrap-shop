<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class NewsCategory extends Model
{
    use HasFactory,
        HasTranslations;

    protected $translatable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::saving(function (NewsCategory $category) {
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
        // Якщо для якоїсь локалі немає slug — підставляємо перший наявний (щоб route не падав)
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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview_web')
            ->width(310)
            ->height(310)
            ->format('png')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
        'slug',
    ];

    protected $casts = [
        'name' => 'json',
        'slug' => 'json',
    ];

    public function getNameUkAttribute()
    {
        return $this->getTranslation('name', 'uk');
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
        // для старих записів без slug — генеруємо з name і зберігаємо
        $generated = $this->generateSlugsFromName();
        if ($generated !== []) {
            $this->setTranslations('slug', array_merge($this->getTranslations('slug'), $generated));
            $this->saveQuietly();

            return (string) reset($generated);
        }

        return null;
    }

    public function news(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(News::class, 'category_id');
    }
}
