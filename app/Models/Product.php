<?php

namespace App\Models;

use App\Models\Traits\HasJsonTranslations;
use App\Services\Pluralize;
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
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        InteractsWithMedia;

    public $translatable = [
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
    ];

    protected $casts = [
        'banner_title' => 'json',
        'slug' => 'json',
        'name' => 'json',
        'description' => 'json',
    ];

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
        if(Auth::check()){
            return $this->wishlists()->where('user_id', Auth::id())->exists();
        }
        if(session()?->has('wishlist')){
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
            ->width(310)
            ->height(310)
            ->format('png')
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

    public function getPrice()
    {
        return $this->prices()->whereHas('type', function ($query) {
            $query->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
        })->value('price');
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
                'default_quantity'
            ])
            ->get();
    }

    public static function getCountProducts($categories, $request, $selectedFilterValues, $arrAttr = null){
      if($request->get('filters')){
        $responseArray['attributes_count'] = $request->get('filters');
      }
      else {
        $responseArray['attributes_count'] = $arrAttr;
      }

      $withPrice = function ($query) {
        $query->where('type_id', DB::table('price_types')
          ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
          ->value('id'))
          ->where('price', '>', 0);
      };

      if($request->get('min_price') && $request->get('max_price')){
        if((int)$request->get('min_price') !== 0 && (int)$request->get('max_price') !== 0){
          $withPrice = function ($query) use ($request) {
            $query->where('type_id', DB::table('price_types')
              ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
              ->value('id'))
              ->where('price', '>=', (int)$request->get('min_price'))
              ->where('price', '<=', (int)$request->get('max_price'));
          };
        }
      }

      $products = self::query()
        ->whereIn('category_id', $categories)
        ->whereHas('attributes')
        ->whereHas('media')
        ->whereHas('prices', $withPrice)
        ->with(['products_attributes' => function ($query) {
          $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
        }])
        ->get();

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

//      $responseArray['total_count'] = $selectedProducts->count();
//      $responseArray['total_count'] = __('product-index.show_products.'. Pluralize::getDeclension($selectedProducts->count(), App::getLocale()), ['count' => $selectedProducts->count()]);


      if(empty($selectedFilterValues) && (int)$request->get('min_price') === 0 && (int)$request->get('max_price') === 0){
        $responseArray['total_count'] = __('product-index.select_filters');
      }
      else{
        $responseArray['total_count'] = __('product-index.show_products.'. Pluralize::getDeclension($selectedProducts->count(), App::getLocale()), ['count' => $selectedProducts->count()]);
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

//      dd($responseArray['attributes_count']);

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

      if ($referer) {
        $parsedUrl = parse_url($referer);
        $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];

        if (isset($parsedUrl['query'])) {
          parse_str($parsedUrl['query'], $queryParams);
          $allowedParams = ['sort_by', 'sort_direction'];
          $sortParams = Arr::only($queryParams, $allowedParams);
        }
      }

      if (empty($newUrl)){
        $newUrl = '';
      }

      if(empty($selectedFilterValues)){
        if(empty($sortParams)){
          $responseArray['new_url'] = $newUrl;
        }
        else {
          $responseArray['new_url'] = $newUrl . '?' .http_build_query($sortParams);
        }
      }
      else{
        if(empty($sortParams)){
          $responseArray['new_url'] = $newUrl . '?' . http_build_query($selectedFilterValues);
        }
        else {
          $responseArray['new_url'] = $newUrl . '?' . http_build_query($selectedFilterValues). '&' .http_build_query($sortParams);
        }
      }

      if((int)$request->get('min_price') !== 0 && (int)$request->get('max_price') !== 0){
        $prices = [
          'min_price' => $request->get('min_price'),
          'max_price' => $request->get('max_price'),
        ];
        if(isset(parse_url($responseArray['new_url'])['query'])) {
          $responseArray['new_url'] .= '&' . http_build_query($prices);
        }
        else {
          $responseArray['new_url'] .= '?' . http_build_query($prices);
        }
      }

      return $responseArray;

    }

    public function getBenefits()
    {
        return $this->attributes()->where('field_name', 'benefits')->first()?->pivot?->value;
    }

    public function getApplication()
    {
        return $this->attributes()->where('field_name', 'application')->first()?->pivot?->value;
    }
}
