<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Implementation;
use App\Models\Product;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected array $availableSortValue = [
        'name',
        'price',
        'is_top_seller',
        'created_at',
    ];

    protected array $availableSortDirection = [
        'asc',
        'desc',
    ];

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
   * @return View
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

    $exampleWorks = Implementation::query()->where('is_active',true)->get();
    $latestCategory = Category::query()
      ->whereHas('products', function ($query) use ($products) {
        $query->whereIn('products.id', $products->pluck('id')->toArray());
      })
      ->get();


    $viewProducts = session()->get('viewProducts', []);
    if (!in_array($product->id, $viewProducts, true)) {
      $viewProducts[] = $product->id;
      session()?->put('viewProducts', $viewProducts);
    }

    return view('base.pages.products.show', [
      'product' => $product,
      'exampleWorks' => $exampleWorks,
      'latestCategory' => $latestCategory,
    ]);
  }

  public function category(Category $category, Category $subcategory = null, Category $subsubcategory = null): View
  {
    if ($subsubcategory) {
      $categories = $subsubcategory->isParent() ? $subsubcategory->children()->pluck('id') : [$subsubcategory->id];
      $childrenCategories = $subsubcategory->children;
    }
     else if ($subcategory) {
       $categories = $subcategory->isParent() ? $subcategory->children()->pluck('id') : [$subcategory->id];
       $childrenCategories = $subcategory->children;
     } else {
      $categories = $category->isParent() ? $category->children()->pluck('id') : [$category->id];
      $childrenCategories = $category->children;
    }

      $sortBy = request()->get('sort_by');
      $sortDirection = request()->get('sort_direction');

      if ($sortBy
          && $sortDirection
          && in_array($sortBy, $this->availableSortValue, true)
          && in_array($sortDirection, $this->availableSortDirection, true)
      ) {
          $sortBy = request()->get('sort_by') === 'name'
              ? 'products.name->' . App::getLocale()
              : $sortBy;
      } else {
          $sortBy = 'id';
          $sortDirection = 'desc';
      }

      $products = Product::query()
          ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
          ->join('price_types', 'product_prices.type_id', '=', 'price_types.id')
          ->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
          ->where('product_prices.price', '>', 0)
          ->whereIn('category_id', $categories)
          ->whereHas('media')
          ->select('products.*', \DB::raw('MAX(product_prices.price) as price'))
          ->groupBy('products.id',
            'products.code',
            'products.external_code',
            'products.external_id',
            'products.barcodes',
            'products.slug',
            'products.article',
            'products.is_top_seller',
            'products.is_best_seller',
            'products.is_active',
            'products.name',
            'products.descriptions',
            'products.category_id',
            'products.created_at',
            'products.updated_at',
            'products.banner_title')
          ->orderBy($sortBy, $sortDirection)
          ->paginate(6);

      foreach ($products as $product) {
        $attributes = $product->getProductAttributes();
      }

    return view('base.pages.products.index', [
      'products' => $products,
      'category' => $category,
      'subcategory' => $subcategory,
      'subsubcategory' => $subsubcategory,
      'childrenCategories' => $childrenCategories,
      'attributes' => $attributes ?? [],
    ]);
  }
}
