<?php

namespace App\Models;

use App\Enums\HomeBlockItemTileSize;
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

class HomeBlockItem extends Model implements HasMedia, Sortable
{
    use HasTranslations;
    use InteractsWithMedia;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    public array $translatable = ['custom_title'];

    protected $fillable = [
        'home_block_id',
        'category_id',
        'tile_size',
        'custom_title',
        'sort_order',
    ];

    protected $casts = [
        'tile_size' => HomeBlockItemTileSize::class,
        'custom_title' => 'array',
        'sort_order' => 'integer',
    ];

    public function getTileSizeEnum(): ?HomeBlockItemTileSize
    {
        if ($this->tile_size instanceof HomeBlockItemTileSize) {
            return $this->tile_size;
        }

        return HomeBlockItemTileSize::tryFrom((string) $this->tile_size);
    }

    protected static function booted(): void
    {
        static::saving(static function (HomeBlockItem $item) {
            if ($item->sort_order === null) {
                $item->sort_order = 0;
            }
        });

        static::saved(static function () {
            HomeIndexCache::flush();
        });

        static::deleted(static function () {
            HomeIndexCache::flush();
        });
    }

    public static function tileFallbackImageUrl(): string
    {
        return asset('assets/img/logo.svg');
    }

    public function homeBlock(): BelongsTo
    {
        return $this->belongsTo(HomeBlock::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quickLinks(): HasMany
    {
        return $this->hasMany(HomeBlockItemQuickLink::class)->orderBy('sort_order');
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('home_block_id', $this->home_block_id);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('custom')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('tile_large')
            ->performOnCollections('custom')
            ->width(720)
            ->height(1080)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('tile_small')
            ->performOnCollections('custom')
            ->width(640)
            ->height(400)
            ->format('webp')
            ->nonQueued();
    }

    public function displayTitle(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $custom = $this->getTranslation('custom_title', $locale);
        if (filled($custom)) {
            return $custom;
        }

        return $this->category?->getTranslation('name', $locale)
            ?? $this->category?->getTranslation('name', 'uk')
            ?? '';
    }

    public function tileImageUrl(bool $large = false): string
    {
        $media = $this->getFirstMedia('custom');
        if ($media !== null) {
            $conversion = $large ? 'tile_large' : 'tile_small';
            if ($media->hasGeneratedConversion($conversion)) {
                return $media->getUrl($conversion);
            }

            return $media->getUrl();
        }

        $fromCategory = $this->category?->getPreviewImage();

        return filled($fromCategory) ? $fromCategory : self::tileFallbackImageUrl();
    }

    public function catalogUrl(?string $locale = null): ?string
    {
        if ($this->category === null) {
            return null;
        }
        $locale = $locale ?: app()->getLocale();

        return route('products.category', ['path' => $this->category->catalogPath($locale)]);
    }
}
