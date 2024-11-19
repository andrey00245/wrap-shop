<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Implementation;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @return View
   */
  public function index(): View
  {
    $products = Product::query()
          ->whereHas('prices', function ($query) {
              $query->where('type_id', function ($subQuery) {
                  $subQuery->select('id')
                      ->from('price_types')
                      ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
              })->where('price', '>', 0);
          })
      ->whereHas('media')
      ->whereHas('category')
      ->with(['media'])
      ->paginate(6);

    $mainCategories = Category::query()->whereNull('parent_id')->get();

    $categories = $products->take(5)->flatMap(function ($product) {
      return $product->categories;
    })->unique('id');
    return view('base.pages.products.index', compact(
        'categories',
        'products',
        'mainCategories'
      )
    );
  }


  /**
   *  Handle the incoming request.
   *
   *  @return View
   */
  public function show(Product $product): View
  {
    $products = Product::query()
      ->whereHas('prices', function ($query) {
        $query->where('type_id', function ($subQuery) {
          $subQuery->select('id')
            ->from('price_types')
            ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
        })->where('price', '>', 0);
      })
      ->whereHas('media')
      ->whereHas('category')
      ->with(['media', 'category'])
      ->paginate(6);

    $categories = $products->take(5)->flatMap(function ($product) {
      return $product->categories;
    })->unique('id');

    $exampleWorks = Implementation::query()->where('is_active',true)->get();
    $latestCategory = Category::query()
      ->whereHas('products', function ($query) use ($products) {
        $query->whereIn('products.id', $products->pluck('id')->toArray());
      })
      ->get();

    return view('base.pages.products.show', [
      'product' => $product,
      'products' => $products,
      'categories' => $categories,
      'exampleWorks' => $exampleWorks,
      'latestCategory' => $latestCategory,
    ]);
  }

    public function category(Category $category): View
    {
        $categories = $category->isParent() ? $category->children()->pluck('id') : [$category->id];

        $products = Product::query()
            ->whereHas('prices', function ($query) {
                $query->where('type_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                })->where('price', '>', 0);
            })
            ->whereHas('media')
            ->whereIn('category_id', $categories)
            ->with(['media'])
            ->paginate(6);

        return view('base.pages.products.index', [
            'products' => $products,
            'category' => $category,
            'categories' => $category->children(),
        ]);
    }
}
