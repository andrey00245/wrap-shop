<?php

namespace App\Models;

use App\Services\Pluralize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;
use Spatie\Image\Enums\Fit;
use Illuminate\Support\Facades\File;

/**
 * @method static Builder whereLikeInsensitive(string $column, string $value)
 * @property $code
 * @property $external_code
 * @property $external_id
 * @property $barcodes
 * @property $article
 * @property $is_top_seller
 * @property $is_active
 * @property $details
 * @property $category_id
 * @property $name
 * @property $slug
 * @property $descriptions
 * @property $banner_title
 * @property $stock
 */
class Product extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia;

    public array $translatable = [
        'name',
        'descriptions',
        'banner_title',
        'slug'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'external_code',
        'external_id',
        'barcodes',
        'article',
        'is_top_seller',
        'is_active',
        'details',
        'category_id',
        'name',
        'slug',
        'descriptions',
        'banner_title',
        'stock'
    ];

    protected $casts = [
        'banner_title' => 'json',
        'slug'         => 'json',
        'name'         => 'json',
        'descriptions' => 'json',
    ];

    /**
     * @param $query
     * @param $column
     * @param $value
     * @return Builder
     */
    public function scopeWhereLikeInsensitive($query, array $columns, string $value): Builder
    {
        $keywords = preg_split('/\s+/', mb_strtolower($value), -1, PREG_SPLIT_NO_EMPTY);

        $allKeywords = [];
        foreach ($keywords as $word) {
            $allKeywords[] = $word;
            $allKeywords[] = static::toTranslit($word);
        }

        $query->where(function ($outerQuery) use ($columns, $allKeywords) {
            foreach ($columns as $column) {
                $outerQuery->orWhere(function ($innerQuery) use ($column, $allKeywords) {
                    foreach ($allKeywords as $keyword) {
                        if (!empty($keyword)) {
                            $innerQuery->orWhereRaw("LOWER(products.{$column}) LIKE ?", ['%' . $keyword . '%'])
                                ->orWhereRaw("LOWER(products.{$column}) LIKE ?", [$keyword . '%']);
                        }
                    }
                });
            }
        });

        return $query;
    }


    protected static function toTranslit(string $text): string
    {
        $map = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'h', 'ґ' => 'g', 'д' => 'd',
            'е' => 'e', 'є' => 'ye', 'ж' => 'zh', 'з' => 'z', 'и' => 'y', 'і' => 'i',
            'ї' => 'yi', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
            'ь' => '', 'ю' => 'yu', 'я' => 'ya',

            'ё' => 'yo', 'э' => 'e', 'ъ' => '',
        ];

        return strtr(mb_strtolower($text), $map);
    }

    public function getSlugEnAttribute()
    {
        return $this->getTranslation('slug', 'en');
    }

    /**
     * Brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function isFavorite(): bool
    {
        if (Auth::check()) {
            return $this->wishlists()->where('user_id', Auth::id())->exists();
        }
        if (session()?->has('wishlist')) {
            return in_array($this->id, session()?->get('wishlist', []), true);
        }
        return false;
    }

    /**
     * Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Wishlists.
     */
    public function wishlists(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Crop, 310, 310) // Указываем корректный enum
            ->format('png')
            ->quality(100) // Улучшение качества
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('banner_images');
    }

    /**
     * Prices.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    /**
     * Attributes.
     */
    public function products_attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Attributes.
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'products_attributes')
            ->using(ProductAttribute::class)
            ->withPivot('value');
    }

    /**
     * @return BelongsTo
     */
    public function purpose(): BelongsTo
    {
        return $this->belongsTo(Purpose::class);
    }

    public function getNameAttribute($value)
    {
        return $value;
    }

    /**
     * @throws \JsonException
     */
    public function getPrice()
    {
        return $this->prices()->whereHas('type', function ($query) {
                $query->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
            })->value('price') * self::getCurrencyRate();
    }

    public function getImage(): string
    {
        return $this->getFirstMediaUrl('images');
    }

    public function getBannerImages()
    {
        return $this->getMedia('banner_images');
    }

    public function getProductAttributes()
    {
        return $this->attributes()
            ->whereNotIn('field_name', [
                'name',
                'application',
                'purpose',
                'benefits',
                'default_quantity',
                'master_qualification',
                'store_terms',
                'warranty',
                'quantity_step',
                'min_order_quantity',
                'first_stock',
                'second_stock',
                'third_stock',
                'under_order'
            ])
            ->get();
    }

    public static function getCountProducts($categories, $request, $selectedFilterValues, $arrAttr = null)
    {
        if ($request->get('filters')) {
            $responseArray['attributes_count'] = $request->get('filters');
        } else {
            $responseArray['attributes_count'] = $arrAttr;
        }

        $withPrice = function ($query) {
            $query->where('type_id', DB::table('price_types')
                ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                ->value('id'))
                ->where('price', '>', 0);
        };

        if ($request->get('min_price') && $request->get('max_price')) {
            if ((int)$request->get('min_price') !== 0 && (int)$request->get('max_price') !== 0) {
                $withPrice = function ($query) use ($request) {
                    $query->where('type_id', DB::table('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                        ->value('id'))
                        ->where('price', '>=', (int)$request->get('min_price') / Product::getCurrencyRate())
                        ->where('price', '<=', (int)$request->get('max_price') / Product::getCurrencyRate());
                };
            }
        }

        $products = collect();

        if ($request->get('search')) {

            $columns = ['name'];
            $searchValue = $request->get('search');
            $includeDescription = $request->boolean('description');
            if ($includeDescription) {
                $columns[] = 'descriptions';
            }

            $products = self::query()
                ->whereIn('category_id', $categories)
                ->when($searchValue, function ($query) use ($columns, $searchValue) {
                    $query->whereLikeInsensitive($columns, $searchValue);
                })
                ->whereHas('attributes')
                ->whereHas('media')
                ->whereHas('prices', $withPrice)
                ->with(['products_attributes' => function ($query) {
                    $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                }])
                ->get();
        } else {
            $products = self::query()
                ->whereIn('category_id', $categories)
                ->whereHas('attributes')
                ->whereHas('media')
                ->whereHas('prices', $withPrice)
                ->with(['products_attributes' => function ($query) {
                    $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                }])
                ->get();
        }

        $selectedProducts = $products->filter(function ($product) use ($selectedFilterValues) {
            foreach ($selectedFilterValues as $key => $filterValues) {
                $matchingValues = [];
                foreach ($product->products_attributes as $products_attribute) {
                    if ($products_attribute->field_name === $key) {
                        $matchingValues = array_intersect($filterValues, (array)$products_attribute->value);
                    }
                }
                if (empty($matchingValues)) {
                    return false;
                }
            }
            return $product;
        });

        if (empty($selectedFilterValues) && (int)$request->get('min_price') === 0 && (int)$request->get('max_price') === 0) {
            $responseArray['total_count'] = __('product-index.select_filters');
        } else {
            $responseArray['total_count'] = __('product-index.show_products.' . Pluralize::getDeclension($selectedProducts->count(), App::getLocale()), ['count' => $selectedProducts->count()]);
        }

        $nonSelectedProducts = $products->filter(function ($product) use ($selectedFilterValues) {
            foreach ($selectedFilterValues as $key => $filterValues) {
                $matchingValues = [];
                foreach ($product->products_attributes as $products_attribute) {
                    if ($products_attribute->field_name === $key) {
                        $matchingValues = array_intersect($filterValues, (array)$products_attribute->value);
                    }
                }
                if (empty($matchingValues)) {
                    return $product;
                }
            }
            return false;
        });

        foreach ($responseArray['attributes_count'] as $key_i => $item) {
            foreach ($item as $key_j => $attribute) {
                if (!array_key_exists('count', (array)$responseArray['attributes_count'][$key_i][$key_j])) {
                    (array)$responseArray['attributes_count'][$key_i][$key_j]['count'] = 0;
                }
            }
        }

        foreach ($selectedProducts as $product) {
            foreach ($product->products_attributes as $products_attribute) {
                if (array_key_exists($products_attribute->field_name, $responseArray['attributes_count'])) {
                    if (isset($responseArray['attributes_count'][$products_attribute->field_name][$products_attribute->value]["count"])) {
                        ++$responseArray['attributes_count'][$products_attribute->field_name][$products_attribute->value]['count'];
                    }
                }
            }
        }

        foreach ($nonSelectedProducts as $product) {
            $testArr = [];
            $valArr = [];
            foreach ($selectedFilterValues as $keyI => $selectedFilterValue) {
                $testArr[] = $keyI;
                foreach ($selectedFilterValue as $filterValue) {
                    $valArr[] = $filterValue;
                }
            }

            $tempAddCount = 0;
            $tempField = '';

            foreach ($product->products_attributes as $products_attribute) {
                if (in_array($products_attribute->field_name, $testArr, true)) {
                    if (in_array($products_attribute->value, $valArr, true)) {
                        $tempAddCount++;
                    } else {
                        $tempField = $products_attribute->field_name;
                    }
                }
            }

            if ($tempAddCount === count($testArr) - 1) {
                foreach ($product->products_attributes as $products_attribute) {
                    if ($products_attribute->field_name === $tempField) {
                        if (isset($responseArray['attributes_count'][$products_attribute->field_name][$products_attribute->value]["count"])) {
                            ++$responseArray['attributes_count'][$products_attribute->field_name][$products_attribute->value]['count'];
                        }
                    }
                }
            }
        }

        $referer = $request->header('referer');
        $sortParams = [];
        $search = [];
        $prices = [];

        if ($referer) {
            $parsedUrl = parse_url($referer);
            $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];

            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
                $allowedParams = ['sort_by', 'sort_direction'];
                $sortParams = Arr::only($queryParams, $allowedParams);
            }
        }

        if (empty($newUrl)) {
            $newUrl = '';
        }

        if (preg_match('/\b' . preg_quote(route('search'), '/') . '\b/u', request()->header('referer'))) {
            if ($request->get('search')) {
                $search = ['search' => $request->get('search')];
            }
            if ($request->get('category_id')) {
                $search += ['category_id' => $request->get('category_id')];
            }
            if ($request->get('sub_category')) {
                $search += ['sub_category' => $request->get('sub_category')];
            }
            if ($request->get('description')) {
                $search += ['description' => $request->get('description')];
            }
        }


        if ((int)$request->get('min_price') !== 0 && (int)$request->get('max_price') !== 0) {
            $prices = [
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
            ];
        }

        $merged = array_merge($search, $prices, $selectedFilterValues, $sortParams);
        if (http_build_query($merged) !== ""){
            $responseArray['new_url'] = $newUrl . '?' . http_build_query($merged);
        }
        else {
            $responseArray['new_url'] = $newUrl;
        }

        return $responseArray;

    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug->en';
    }

    public function getBenefits()
    {
        return $this->attributes()->where('field_name', 'benefits')->first()?->pivot?->value;
    }

    public function getApplication()
    {
        return $this->attributes()->where('field_name', 'application')->first()?->pivot?->value;
    }

    public function getDefaultQuantity()
    {
        return $this->attributes()->where('field_name', 'default_quantity')->first()?->pivot?->value ?? 1;
    }

    public function getFirstStock()
    {
        return $this->attributes()->where('field_name', 'first_stock')->first()?->pivot?->value;
    }

    public function getSecondStock()
    {
        return $this->attributes()->where('field_name', 'second_stock')->first()?->pivot?->value;
    }

    public function getThirdStock()
    {
        return $this->attributes()->where('field_name', 'third_stock')->first()?->pivot?->value;
    }

    public function getStock(): float
    {
        return (float)$this->stock;
    }

    public function getSmallPrice(): float
    {
        return $this->prices()->whereHas('type', function ($query) {
                $query->where('price_types.external_id', 'bb2a9b0f-26f6-11ee-0a80-0f50000d072f');
            })->value('price') * self::getCurrencyRate();
    }

    public function getBigPrice(): float
    {
        return $this->prices()->whereHas('type', function ($query) {
                $query->where('price_types.external_id', 'bb2a9b91-26f6-11ee-0a80-0f50000d0730');
            })->value('price') * self::getCurrencyRate();
    }

    public function getWarranty()
    {
        return $this->attributes()->where('field_name', 'warranty')->first()?->pivot?->value;
    }

    public function getStoreTerms()
    {
        return $this->attributes()->where('field_name', 'store_terms')->first()?->pivot?->value;
    }

    public function getMainColor()
    {
        return $this->attributes()->where('field_name', 'main_shade')->first()?->pivot?->value;
    }

    public function getBrand()
    {
        return $this->attributes()->where('field_name', 'brand')->first()?->pivot?->value;
    }

    public function getRoomTemperature()
    {
        return $this->attributes()->where('field_name', 'room_temperature')->first()?->pivot?->value;
    }

    public function getMasterQualification()
    {
        return $this->attributes()->where('field_name', 'master_qualification')->first()?->pivot?->value;
    }

    public function getPriceByDollars($price)
    {
        return round($price / self::getCurrencyRate(), 0);
    }

    public function customBlocks()
    {
        return $this->belongsToMany(CustomBlock::class);
    }

    public function getMinOrderCount()
    {
        return $this->attributes()->where('field_name', 'min_order_quantity')->first()?->pivot?->value ?? 1;
    }

    public function getOrderStep()
    {
        return $this->attributes()->where('field_name', 'quantity_step')->first()?->pivot?->value ?? 1;
    }

    public function getUnderOrder()
    {
        return $this->attributes()->where('field_name', 'under_order')->first()?->pivot?->value;
    }

    public function getRollSize()
    {
        return $this->attributes()->where('field_name', 'roll_size')->first()?->pivot?->value;
    }

    public function getStructure()
    {
        return $this->attributes()->where('field_name', 'structure')->first();
    }

    public function getPurpose()
    {
        return $this->attributes()->where('field_name', 'purpose')->first();
    }

    public function getName()
    {
        return $this->attributes()->where('field_name', 'name')->first()?->pivot?->value
            ?? $this->name;
    }

    public function getType()
    {
        return $this->attributes()->where('field_name', 'type')->first();
    }

    /**
     * @throws \JsonException
     */
    public function getPriceByCount($count)
    {
        $productPrice = $this->getPrice();

        if ($this->getRollSize()) {

            if ($count >= $this->getSecondStock() && $count <= $this->getThirdStock()){
                $productPrice = $this->getSmallPrice();
            }

            if ($count >= $this->getThirdStock() ){
                $productPrice = $this->getBigPrice();
            }

            return $productPrice * $count;
        }

        return $productPrice * $count;
    }

    public static function getCurrencyRate()
    {
        $currency = Setting::query()->value('currency');
        return $currency ?? 42;
    }

    public function volumeVariants()
    {
        return $this->belongsToMany(Product::class, 'product_volume_variants', 'product_id', 'variant_product_id');
    }

    public function isVolumeVariantOf()
    {
        return $this->belongsToMany(Product::class, 'product_volume_variants', 'variant_product_id', 'product_id');
    }

    public function allVolumeVariants()
    {
        return $this->volumeVariants->merge($this->isVolumeVariantOf);
    }

    public function hasVolumeAttribute()
    {
        return $this->attributes()->where('field_name', 'volume')->exists();
    }

    public function scopeHasVolume($query)
    {
        return $query->whereHas('attributes', function ($q) {
            $q->where('field_name', 'volume');
        });
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getIsTranslatedAttribute(): bool
    {
        $nameRu = $this->getTranslation('name', 'ru');
        $nameEn = $this->getTranslation('name', 'en');
        $descRu = $this->getTranslation('descriptions', 'ru');
        $descEn = $this->getTranslation('descriptions', 'en');

        if (
            empty(strip_tags($nameRu)) ||
            empty(strip_tags($nameEn)) ||
            empty(strip_tags($descRu)) ||
            empty(strip_tags($descEn))
        ) {
            return false;
        }

        if (
            trim(strip_tags($nameRu)) === trim(strip_tags($nameEn)) ||
            trim(strip_tags($descRu)) === trim(strip_tags($descEn))
        ) {
            return false;
        }

        return true;
    }

}
