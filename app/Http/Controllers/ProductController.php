<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Implementation;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use function Doctrine\DBAL\Query\andWhere;
use function PHPUnit\Framework\isEmpty;

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

    $exampleWorks = Implementation::query()->where('is_active', true)->get();
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
    $selectedFilterValues = request()->all();
    unset($selectedFilterValues['page'],
      $selectedFilterValues['sort_by'],
      $selectedFilterValues['sort_direction'],
      $selectedFilterValues['min_price'],
      $selectedFilterValues['max_price']);

    if ($subsubcategory) {
      $categories = $subsubcategory->isParent() ? $subsubcategory->children()->pluck('id') : [$subsubcategory->id];
      $childrenCategories = $subsubcategory->children;
    } else if ($subcategory) {
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
      ->groupBy('products.id')
      ->orderBy($sortBy, $sortDirection)
      ->with(['products_attributes' => function ($query) {
        $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
      }]);

    $temp = $products->get();

    $maxPrice = $temp->max('price');
    $minPrice = $temp->min('price');
    $step = ceil(($maxPrice - $minPrice) / 4);

    if(request()->get('min_price') && request()->get('max_price')){
      $productsAllCollection = $products
        ->where('product_prices.price', '>=', request()->get('min_price'))
        ->where('product_prices.price', '<=', request()->get('max_price'))
        ->get();
    }
    else {
      $productsAllCollection = $temp;
    }

    $productsAllCollection = $productsAllCollection->filter(function ($product) use ($selectedFilterValues) {
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

    $perPage = 18;
    $attributes = collect();

    foreach ($productsAllCollection as $product) {
      $attributes[] = $product->getProductAttributes();
    }

    $products = new LengthAwarePaginator($productsAllCollection->forPage(request()->get('page'), $perPage), $productsAllCollection->count(), $perPage, request()->get('page'), ['path' => url()->current(), 'pageName' => 'page']);

    $attributesArray = [];

    foreach ($attributes->flatten()->unique('field_name') as $attribure){
      foreach ($attribure->getPivotValue() as $value){
        $attributesArray[$attribure->field_name][$value]['count'] = 0;
      }
    }

    $responseArray = Product::getCountProducts($categories, request(), $selectedFilterValues, $attributesArray);

    return view('base.pages.products.index', [
      'products' => $products,
      'category' => $category,
      'subcategory' => $subcategory,
      'subsubcategory' => $subsubcategory,
      'childrenCategories' => $childrenCategories,
      'minPrice' => $minPrice,
      'maxPrice' => $maxPrice,
      'step' => $step,
      'attributes' => $attributes->flatten()->unique('field_name') ?? collect(),
      'responseArray' => $responseArray,
    ]);
  }

  /**
   * @throws \JsonException
   */
  public function getCount(Request $request)
  {
    $selectedFilterValues = [];
    $categories = [];

    $category = Category::query()->where('id', $request->get('category_id'))->first();
    if ($category->hasChildren()) {
      $categories = $category->children()->pluck('id');
    } else {
      $categories[] = $category->id;
    }

    foreach ($request->get('filters') as $key => $filterType) {
      foreach ($filterType as $key_i => $filterValue) {
        if (in_array('true', $filterValue, true)) {
          $selectedFilterValues[$key][] = $key_i;
        }
      }
    }

    return response()->json(Product::getCountProducts($categories, $request, $selectedFilterValues));
  }

}
