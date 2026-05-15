<?php

namespace App\Models;

use App\Enums\HomeBlockItemTileSize;
use App\Enums\HomeBlockType;
use App\Enums\KitsCatalogMode;
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

    public array $translatable = ['custom_title', 'custom_tagline', 'kit_description'];

    protected $fillable = [
        'home_block_id',
        'category_id',
        'product_id',
        'tile_size',
        'custom_title',
        'custom_tagline',
        'kit_description',
        'kits_catalog_mode',
        'sort_order',
    ];

    protected $casts = [
        'tile_size' => HomeBlockItemTileSize::class,
        'custom_title' => 'array',
        'custom_tagline' => 'array',
        'kit_description' => 'array',
        'kits_catalog_mode' => KitsCatalogMode::class,
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

            $block = HomeBlock::query()->find($item->home_block_id);
            if ($block?->getTypeEnum() !== HomeBlockType::Kits) {
                $item->kits_catalog_mode = null;
                $item->kit_description = null;

                return;
            }

            if ($item->kits_catalog_mode === null) {
                $item->kits_catalog_mode = KitsCatalogMode::Flat;
            }
        });

        static::saved(static function (HomeBlockItem $item) {
            $block = HomeBlock::query()->find($item->home_block_id);

            if ($block?->getTypeEnum() !== HomeBlockType::Kits) {
                if ($item->kitLines()->exists() || $item->kitGroups()->exists()) {
                    $item->kitLines()->delete();
                    $item->kitGroups()->delete();
                }
            } elseif ($item->kits_catalog_mode === KitsCatalogMode::Flat) {
                if ($item->kitGroups()->exists()) {
                    $item->kitGroups()->delete();
                }
            }

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function quickLinks(): HasMany
    {
        return $this->hasMany(HomeBlockItemQuickLink::class)->orderBy('sort_order');
    }

    public function kitGroups(): HasMany
    {
        return $this->hasMany(HomeBlockKitGroup::class)->orderBy('sort_order');
    }

    public function kitLines(): HasMany
    {
        return $this->hasMany(HomeBlockKitLine::class)->orderBy('sort_order');
    }

    public function getResolvedKitsCatalogMode(): KitsCatalogMode
    {
        if ($this->kits_catalog_mode instanceof KitsCatalogMode) {
            return $this->kits_catalog_mode;
        }

        $try = KitsCatalogMode::tryFrom((string) $this->kits_catalog_mode);
        if ($try !== null) {
            return $try;
        }

        if ($this->relationLoaded('kitGroups')) {
            return $this->kitGroups->isNotEmpty() ? KitsCatalogMode::Grouped : KitsCatalogMode::Flat;
        }

        return $this->kitGroups()->exists() ? KitsCatalogMode::Grouped : KitsCatalogMode::Flat;
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{label: ?string, products: \Illuminate\Support\Collection<int, \App\Models\Product>}>
     */
    public function kitsProductGroupsForDisplay(?string $locale = null): \Illuminate\Support\Collection
    {
        $locale = $locale ?: app()->getLocale();

        if ($this->kitsHasManualProductLines()) {
            return $this->kitsBuildManualProductGroups($locale);
        }

        return $this->kitsBuildCategoryFallbackGroups($locale);
    }

    private function kitsHasManualProductLines(): bool
    {
        if (! $this->relationLoaded('kitLines')) {
            return false;
        }

        return $this->kitLines->whereNotNull('product_id')->isNotEmpty();
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{label: ?string, products: \Illuminate\Support\Collection<int, \App\Models\Product>}>
     */
    private function kitsBuildManualProductGroups(string $locale): \Illuminate\Support\Collection
    {
        $lines = $this->kitLines
            ->sortBy('sort_order')
            ->filter(fn (HomeBlockKitLine $l) => $l->product)
            ->values();

        if ($this->getResolvedKitsCatalogMode() === KitsCatalogMode::Flat) {
            $flat = $lines->map->product->filter()->values();

            return collect([['label' => null, 'products' => $flat]]);
        }

        $groupsDef = $this->relationLoaded('kitGroups')
            ? $this->kitGroups->sortBy('sort_order')->values()
            : collect();

        $out = collect();

        $ungrouped = $lines->whereNull('home_block_kit_group_id')->map->product->filter()->values();
        if ($ungrouped->isNotEmpty()) {
            $out->push(['label' => null, 'products' => $ungrouped]);
        }

        foreach ($groupsDef as $g) {
            $prods = $lines->where('home_block_kit_group_id', $g->id)->map->product->filter()->values();
            if ($prods->isEmpty()) {
                continue;
            }
            $lbl = $g->getTranslation('title', $locale) ?: $g->getTranslation('title', 'uk') ?: '';

            $out->push(['label' => $lbl !== '' ? $lbl : null, 'products' => $prods]);
        }

        return $out;
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{label: ?string, products: \Illuminate\Support\Collection<int, \App\Models\Product>}>
     */
    private function kitsBuildCategoryFallbackGroups(string $locale): \Illuminate\Support\Collection
    {
        $cat = $this->category;
        if ($cat === null) {
            return collect();
        }

        $children = $cat->relationLoaded('children') ? $cat->children : collect();

        if ($children->isNotEmpty()) {
            $groups = $children->map(function (Category $child) use ($locale) {
                $products = $child->relationLoaded('products')
                    ? $child->products->filter(fn (Product $p) => (bool) $p->is_active)->sortBy('id')->values()
                    : collect();

                if ($products->isEmpty()) {
                    return null;
                }

                return [
                    'label' => $child->getTranslation('name', $locale) ?: $child->getTranslation('name', 'uk') ?: '',
                    'products' => $products,
                ];
            })->filter()->values();

            if ($groups->isNotEmpty()) {
                return $groups;
            }
        }

        $flat = $cat->relationLoaded('products')
            ? $cat->products->filter(fn (Product $p) => (bool) $p->is_active)->sortBy('id')->take(80)->values()
            : collect();

        return collect([['label' => null, 'products' => $flat]]);
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('home_block_id', $this->home_block_id);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('custom_image')->singleFile();
        $this->addMediaCollection('custom')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('tile_large')
            ->performOnCollections('custom_image', 'custom')
            ->width(720)
            ->height(1080)
            ->format('webp')
            ->nonQueued();

        $this
            ->addMediaConversion('tile_small')
            ->performOnCollections('custom_image', 'custom')
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

        $productName = $this->product?->getName();
        if (filled($productName)) {
            return $productName;
        }

        return $this->category?->getTranslation('name', $locale)
            ?? $this->category?->getTranslation('name', 'uk')
            ?? '';
    }

    public function displayTagline(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $custom = $this->getTranslation('custom_tagline', $locale);
        if (filled($custom)) {
            return $custom;
        }

        return (string) ($this->product?->category?->getTranslation('name', $locale)
            ?? $this->product?->category?->getTranslation('name', 'uk')
            ?? '');
    }

    public function tileImageUrl(bool $large = false): string
    {
        $media = $this->getFirstMedia('custom_image') ?? $this->getFirstMedia('custom');
        if ($media !== null) {
            $conversion = $large ? 'tile_large' : 'tile_small';
            if ($media->hasGeneratedConversion($conversion)) {
                return $media->getUrl($conversion);
            }

            return $media->getUrl();
        }

        $fromCategory = $this->category?->getPreviewImage();
        if (filled($this->product?->getPreviewImage())) {
            return $this->product->getPreviewImage();
        }

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
