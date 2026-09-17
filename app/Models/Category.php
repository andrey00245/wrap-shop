<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia, Sortable
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia,
        SortableTrait {
            scopeOrdered as scopeSortableOrder;
        }

    public array $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
        'nova_order_by' => 'ASC',
    ];

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
        'sort_order',
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
        'sort_order' => 'integer',
    ];

    /**
     * @return list<array{question: string, answer: string}>
     */
    public function faqForSchema(?string $locale = null): array
    {
        return \App\Support\LocaleFaqItems::forLocale(
            is_array($this->faq_items) ? $this->faq_items : null,
            $locale ?: app()->getLocale()
        );
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->width(310)
            ->height(310)
            ->nonQueued()
            ->performOnCollections('main');

        $this
            ->addMediaConversion('preview_webp')
            ->width(310)
            ->height(310)
            ->format('webp')
            ->nonQueued()
            ->performOnCollections('main');

        $this
            ->addMediaConversion('menu_thumb')
            ->width(96)
            ->height(96)
            ->nonQueued()
            ->performOnCollections('menu_icon');

        $this
            ->addMediaConversion('menu_thumb_webp')
            ->width(96)
            ->height(96)
            ->format('webp')
            ->nonQueued()
            ->performOnCollections('menu_icon');
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
        $this->addMediaCollection('menu_icon')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']);
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
        $relation = $this->hasMany(Category::class, 'parent_id');

        return static::applyMenuOrder($relation);
    }

    public function buildSortQuery(): Builder
    {
        $query = static::query();

        if ($this->parent_id === null) {
            return $query->whereNull('parent_id');
        }

        return $query->where('parent_id', $this->parent_id);
    }

    public function scopeMenuOrdered(Builder $query): Builder
    {
        return static::applyMenuOrder($query);
    }

    public static function applyMenuOrder(Builder|Relation $query): Builder|Relation
    {
        if (static::hasSortOrderColumn()) {
            return $query->orderBy('sort_order')->orderBy('id');
        }

        return $query->orderBy('id');
    }

    public static function hasSortOrderColumn(): bool
    {
        static $hasColumn = null;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn((new static)->getTable(), 'sort_order');
        }

        return $hasColumn;
    }

    public static function catalogMenuTree(): \Illuminate\Database\Eloquent\Collection
    {
        $categories = static::query()
            ->whereNull('parent_id')
            ->menuOrdered()
            ->with([
                'children' => fn ($q) => $q->menuOrdered()->with([
                    'children' => fn ($q2) => $q2->menuOrdered(),
                ]),
            ])
            ->get();

        static::attachMenuSpotlightProducts($categories);

        return $categories;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Category>  $rootCategories
     */
    public static function attachMenuSpotlightProducts($rootCategories, int $limit = 5): void
    {
        if ($rootCategories->isEmpty()) {
            return;
        }

        $categoryIdMap = [];
        foreach ($rootCategories as $root) {
            $ids = [$root->id];
            foreach ($root->children ?? [] as $child) {
                $ids[] = $child->id;
                foreach ($child->children ?? [] as $grandChild) {
                    $ids[] = $grandChild->id;
                }
            }
            $categoryIdMap[$root->id] = array_values(array_unique($ids));
        }

        $allCategoryIds = collect($categoryIdMap)->flatten()->unique()->values()->all();
        if ($allCategoryIds === []) {
            return;
        }

        $bestSellerIds = BestSeller::query()
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->all();

        $products = Product::query()
            ->where('is_active', 1)
            ->where('stock', '>', 0)
            ->whereIn('category_id', $allCategoryIds)
            ->withSum('orderLines as sold_qty', 'quantity')
            ->withCount([
                'reviews as approved_reviews_count' => function ($query) {
                    $query->where('is_active', true)
                        ->where('moderation_status', 'approved');
                },
            ])
            ->get([
                'id',
                'category_id',
                'name',
                'slug',
                'is_active',
                'stock',
                'is_best_seller',
            ]);

        $pickedIdsByRoot = [];

        foreach ($rootCategories as $root) {
            $scopeIds = $categoryIdMap[$root->id] ?? [];
            $scoped = $products
                ->filter(fn (Product $product) => in_array((int) $product->category_id, $scopeIds, true))
                ->values();

            $picked = collect();

            $bySales = $scoped
                ->filter(function (Product $product) use ($bestSellerIds) {
                    $sold = (float) ($product->sold_qty ?? 0);

                    return $sold > 0
                        || (bool) $product->is_best_seller
                        || in_array((int) $product->id, $bestSellerIds, true);
                })
                ->sortByDesc(function (Product $product) use ($bestSellerIds) {
                    $sold = (float) ($product->sold_qty ?? 0);
                    $flag = ((bool) $product->is_best_seller || in_array((int) $product->id, $bestSellerIds, true)) ? 1000000 : 0;

                    return $sold + $flag;
                })
                ->values();

            foreach ($bySales as $product) {
                if ($picked->count() >= $limit) {
                    break;
                }
                $picked->push($product);
            }

            if ($picked->count() < $limit) {
                $pickedIds = $picked->pluck('id')->all();
                $byReviews = $scoped
                    ->filter(function (Product $product) use ($pickedIds) {
                        return ! in_array($product->id, $pickedIds, true)
                            && (int) ($product->approved_reviews_count ?? 0) > 0;
                    })
                    ->sortByDesc(fn (Product $product) => (int) ($product->approved_reviews_count ?? 0))
                    ->values();

                foreach ($byReviews as $product) {
                    if ($picked->count() >= $limit) {
                        break;
                    }
                    $picked->push($product);
                }
            }

            if ($picked->count() < $limit) {
                $pickedIds = $picked->pluck('id')->all();
                $fallback = $scoped
                    ->reject(fn (Product $product) => in_array($product->id, $pickedIds, true))
                    ->sortBy('id')
                    ->values();

                foreach ($fallback as $product) {
                    if ($picked->count() >= $limit) {
                        break;
                    }
                    $picked->push($product);
                }
            }

            $pickedIdsByRoot[$root->id] = $picked->take($limit)->pluck('id')->all();
        }

        $allPickedIds = collect($pickedIdsByRoot)->flatten()->unique()->filter()->values()->all();
        $hydrated = $allPickedIds === []
            ? collect()
            : Product::query()
                ->whereIn('id', $allPickedIds)
                ->with([
                    'media',
                    'prices.type',
                    'attributes',
                ])
                ->get()
                ->keyBy('id');

        foreach ($rootCategories as $root) {
            $items = collect($pickedIdsByRoot[$root->id] ?? [])
                ->map(fn ($id) => $hydrated->get($id))
                ->filter()
                ->values();

            $root->setRelation('menuSpotlightProducts', $items);
        }
    }

    /**
     * @return list<array{name: string, image: string, price: string, url: string}>
     */
    public function menuSpotlightCards(): array
    {
        $products = $this->relationLoaded('menuSpotlightProducts')
            ? $this->menuSpotlightProducts
            : collect();

        $cards = [];

        foreach ($products->take(5) as $product) {
            if (! $product instanceof Product) {
                continue;
            }

            $name = trim((string) $product->getName());
            if ($name === '') {
                continue;
            }

            $priceValue = $product->getPrice();
            $price = is_numeric($priceValue) && (float) $priceValue > 0
                ? number_format((float) $priceValue, 0, '.', ' ').' ₴'
                : '';

            $image = $product->getPreviewImage();
            if (! filled($image)) {
                $image = $product->getImage();
            }
            if (! filled($image)) {
                $image = asset('assets/img/logo.svg');
            }

            $slug = $product->slugEn ?: $product->getTranslation('slug', app()->getLocale());
            if (! filled($slug)) {
                continue;
            }

            $cards[] = [
                'name' => $name,
                'image' => $image,
                'price' => $price,
                'url' => route('products.show', ['product' => $slug]),
            ];
        }

        return $cards;
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

    public function getMenuIcon(): string
    {
        $media = $this->getFirstMedia('menu_icon');
        if (! $media) {
            return '';
        }

        if ($media->hasGeneratedConversion('menu_thumb_webp')) {
            return $media->getUrl('menu_thumb_webp');
        }

        if ($media->hasGeneratedConversion('menu_thumb')) {
            return $media->getUrl('menu_thumb');
        }

        return $media->getUrl();
    }

    /**
     * Menu icon → category preview/photo → empty (blade falls back to logo).
     */
    public function resolveMenuIconUrl(): string
    {
        $menuIcon = $this->getMenuIcon();
        if (filled($menuIcon)) {
            return $menuIcon;
        }

        $preview = $this->getPreviewImage();
        if (filled($preview)) {
            return $preview;
        }

        $image = $this->getImage();
        if (filled($image)) {
            return $image;
        }

        return '';
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
