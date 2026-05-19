<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Kit extends Model implements HasMedia, Sortable
{
    use HasTranslations;
    use InteractsWithMedia;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public array $translatable = ['title', 'tagline', 'description'];

    protected $fillable = [
        'kit_section_id',
        'title',
        'tagline',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'tagline' => 'array',
        'description' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(static function (Kit $kit) {
            if ($kit->kit_section_id === null) {
                $kit->kit_section_id = KitSection::defaultId();
            }
            if ($kit->sort_order === null) {
                $kit->sort_order = 0;
            }
        });

        static::saving(static function (Kit $kit) {
            if ($kit->sort_order === null) {
                $kit->sort_order = 0;
            }
        });

        static::saved(static function () {
            HomeIndexCache::flush();
        });

        static::deleted(static function () {
            HomeIndexCache::flush();
        });
    }

    public function buildSortQuery(): Builder
    {
        return static::query();
    }

    public function kitSection(): BelongsTo
    {
        return $this->belongsTo(KitSection::class);
    }

    public function kitGroups(): HasMany
    {
        return $this->hasMany(KitGroup::class)->orderBy('sort_order');
    }

    public function kitLines(): HasMany
    {
        return $this->hasMany(KitLine::class)->orderBy('sort_order');
    }

    /** Товари набору без підгрупи (основний список). */
    public function ungroupedKitLines(): HasMany
    {
        return $this->hasMany(KitLine::class)
            ->whereNull('kit_group_id')
            ->orderBy('sort_order');
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{label: ?string, products: \Illuminate\Support\Collection<int, Product>}>
     */
    public function productGroupsForDisplay(?string $locale = null): \Illuminate\Support\Collection
    {
        $locale = $locale ?: app()->getLocale();
        $out = collect();

        $ungroupedProducts = $this->resolveKitLinesCollection()
            ->whereNull('kit_group_id')
            ->sortBy('sort_order')
            ->map->product
            ->filter()
            ->values();

        if ($ungroupedProducts->isNotEmpty()) {
            $out->push(['label' => null, 'products' => $ungroupedProducts]);
        }

        $groupsDef = $this->relationLoaded('kitGroups')
            ? $this->kitGroups->sortBy('sort_order')->values()
            : $this->kitGroups()->orderBy('sort_order')->get();

        foreach ($groupsDef as $group) {
            $prods = $this->resolveKitLinesCollection()
                ->where('kit_group_id', $group->id)
                ->sortBy('sort_order')
                ->map->product
                ->filter()
                ->values();

            if ($prods->isEmpty()) {
                continue;
            }

            $lbl = $group->getTranslation('title', $locale) ?: $group->getTranslation('title', 'uk') ?: '';

            $out->push(['label' => $lbl !== '' ? $lbl : null, 'products' => $prods]);
        }

        return $out;
    }

    private function resolveKitLinesCollection(): \Illuminate\Support\Collection
    {
        if ($this->relationLoaded('kitLines')) {
            return $this->kitLines;
        }

        return $this->kitLines()->with('product')->get();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('tile_large')
            ->performOnCollections('image')
            ->width(720)
            ->height(1080)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('tile_small')
            ->performOnCollections('image')
            ->width(640)
            ->height(400)
            ->format('webp')
            ->nonQueued();
    }

    public function displayTitle(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return (string) ($this->getTranslation('title', $locale) ?: $this->getTranslation('title', 'uk') ?: '');
    }

    public function displayTagline(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $custom = $this->getTranslation('tagline', $locale);

        return filled($custom) ? $custom : 'STARTER KIT';
    }

    public function getTitleForNovaAttribute(): string
    {
        $title = $this->getTranslation('title', 'uk') ?: $this->getTranslation('title', app()->getLocale()) ?: '';

        return trim((string) $title) !== '' ? $title : 'Набір #'.$this->id;
    }
}
