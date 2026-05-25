<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasFactory,
        HasTranslations;

    public $translatable = ['name', 'values'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'field_name',
        'name',
        'is_visible',
    ];

    protected $casts = [
        'name' => 'json',
        'is_visible' => 'boolean',
    ];

    public function productsVisible($categoryId): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'products_attributes', 'attribute_id', 'product_id')
            ->where('category_id', $categoryId)
            ->whereHas('media')
            ->whereHas('prices', function ($query) {
                $query->where('type_id', DB::table('price_types')
                    ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                    ->value('id'))
                    ->where('price', '>', 0);
            })->withPivot('value');
    }

    /**
     * Attributes.
     */
    public function attributesValues(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'products_attributes')
            ->withPivot('value');
    }

    public function getPivotValue()
    {
        $route = request()->route();
        $category = $route
            ? ($route->parameter('subsubcategory')
                ?? $route->parameter('subcategory')
                ?? $route->parameter('category'))
            : null;

        $searchCategories = collect();

        if (request()->get('category_id')) {
            if (request()->get('category_id') === '0') {
                $searchCategories = Category::all()->pluck('id');
            } else {
                if (request()->get('sub_category') === 'true') {

                    $searchCategories->push((int) request()->get('category_id'));
                    $searchCategory = Category::query()->where('id', request()->get('category_id'))
                        ->first()
                        ->getAllChildren()
                        ->pluck('id');
                    $searchCategories = $searchCategories->merge($searchCategory)->values();
                } else {
                    $searchCategories->push((int) request()->get('category_id'));
                }
            }
        }

        $currentCategoryIds = request()->attributes->get('product_listing_category_ids');
        if ($currentCategoryIds === null && $category !== null) {
            // Для категорії з підкатегоріями беремо всі нащадки, інакше — тільки поточну
            $currentCategoryIds = $category->hasChildren()
                ? $category->allDescendantIds()
                : [$category->id];
        }

        $columns = ['name'];
        $searchValue = request()->get('search');
        $includeDescription = request()->boolean('description');
        if ($includeDescription) {
            $columns[] = 'descriptions';
        }

        $restrictSearchToAlgoliaIds = request()->routeIs('search')
            && request()->attributes->has('algolia_search_product_ids');

        $result = $this->attributesValues()
            ->whereHas('prices', function ($query) {
                $query->where('type_id', DB::table('price_types')
                    ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                    ->value('id'))
                    ->where('price', '>', 0);
            })
            ->whereHas('media')
            ->when($currentCategoryIds !== null, function ($query) use ($currentCategoryIds) {
                return $query->whereIn('category_id', $currentCategoryIds);
            })
            ->when($searchCategories->isNotEmpty(), function ($query) use ($searchCategories) {
                return $query->whereIn('category_id', $searchCategories);
            })
            ->when(
                $restrictSearchToAlgoliaIds,
                function ($query) {
                    $ids = request()->attributes->get('algolia_search_product_ids');
                    if (! is_array($ids) || $ids === []) {
                        $query->whereRaw('0 = 1');

                        return;
                    }
                    $ids = array_values(array_unique(array_map('intval', $ids)));
                    $query->whereIn('products.id', $ids);
                },
                function ($query) use ($columns, $searchValue) {
                    if ($searchValue) {
                        $query->whereLikeInsensitive($columns, $searchValue);
                    }
                }
            )

            ->get()
            ->map(fn ($product) => Product::extractPivotValueForDisplay($product->pivot->value))
            ->filter()
            ->groupBy(fn ($value) => Product::normalizePivotValueForCatalogFacetKey($value))
            ->map(fn ($group) => Product::pickCanonicalAttributeDisplayValue($group))
            ->filter(fn ($value) => $value !== '')
            ->values();

        return $result;
    }

    public function getDefaultProductsCount($attributeId, $value)
    {
        $route = request()->route();
        $category = $route
            ? ($route->parameter('subsubcategory')
                ?? $route->parameter('subcategory')
                ?? $route->parameter('category'))
            : null;

        $productIds = ProductAttribute::query()
            ->where('attribute_id', $attributeId)
            ->whereJsonContains('value->'.App::getLocale(), $value)
            ->pluck('product_id')
            ->toArray();

        $categoryIds = [];
        if ($category) {
            $categoryIds = $category->hasChildren()
                ? $category->allDescendantIds()
                : [$category->id];
        }

        return Product::query()
            ->where('is_active', 1)
            ->whereIn('id', $productIds)
            ->when(! empty($categoryIds), function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->whereHas('media')
            ->whereHas('prices', function ($query) {
                $query->where('type_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                })->where('price', '>', 0);
            })->count();
    }

    /**
     * Проверяет, переведен ли атрибут на все языки
     */
    public function getIsTranslatedAttribute(): bool
    {
        $nameRu = $this->getTranslation('name', 'ru');
        $nameEn = $this->getTranslation('name', 'en');

        // Проверяем, что переводы существуют
        if (empty($nameRu) || empty($nameEn)) {
            return false;
        }

        // Проверяем, что переводы не одинаковые
        if (trim($nameRu) === trim($nameEn)) {
            return false;
        }

        return true;
    }
}
