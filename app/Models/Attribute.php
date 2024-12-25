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
    ];

    protected $casts = [
        'name' => 'json',
    ];

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
        $category = request()->route()->parameter('subsubcategory')
            ?? request()->route()->parameter('subcategory')
            ?? request()->route()->parameter('category');

        $currentCategory = $category->isParent() ? $category->children()->pluck('id') : [$category->id];

        return $this->attributesValues()
            ->whereHas('prices', function ($query) {
                $query->where('type_id', DB::table('price_types')
                    ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                    ->value('id'))
                    ->where('price', '>', 0);
            })
            ->whereHas('media')
            ->whereIn('category_id', $currentCategory)
            ->get()
            ->map(function ($product) {
                return data_get(json_decode($product->pivot->value), App::getLocale());
            })
            ->filter()
            ->unique();
    }

    public function getDefaultProductsCount($attributeId, $value) {
       $category = request()->route()->parameter('subsubcategory')
           ?? request()->route()->parameter('subcategory')
           ?? request()->route()->parameter('category');

        $productIds = ProductAttribute::query()
            ->where('attribute_id', $attributeId)
            ->whereJsonContains('value->'.App::getLocale(), $value)
           ->pluck('product_id')->toArray();

      return Product::query()
          ->whereIn('id', $productIds)
          ->whereHas('media')
          ->whereIn('category_id', $category->isParent() ? $category->children()->pluck('id') : [$category->id])
          ->whereHas('prices', function ($query) {
              $query->where('type_id', function ($subQuery) {
                  $subQuery->select('id')
                      ->from('price_types')
                      ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
              })->where('price', '>', 0);
          })->count();
    }
}
