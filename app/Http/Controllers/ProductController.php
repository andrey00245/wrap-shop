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
    unset($selectedFilterValues['page']);
//
//    foreach (request()->all() as $key => $filterType) {
//      foreach ($filterType as $filterValue) {
//        $selectedFilterValues[$key][] = $filterValue;
//      }
//    }
//
//    dd($selectedFilterValues);

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
//    $products->paginate(1);

//      dump($products->paginate(1));




//    $products = Product::query()
//      ->whereIn('category_id', $categories)
//      ->whereHas('attributes')
//      ->whereHas('media')
//      ->whereHas('prices', function ($query) {
//        $query->where('type_id', DB::table('price_types')
//          ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
//          ->value('id'))
//          ->where('price', '>', 0);
//      })
//      ->with(['products_attributes' => function ($query) {
//        $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
//      }])
//      ->get();





    $productsAllCollection = $products->get();


    $productsAllCollection = $productsAllCollection->filter(function ($product) use ($selectedFilterValues) {
      foreach ($selectedFilterValues as $key => $filterValues) {
//        dump($selectedFilterValues);

        $matchingValues = [];
        foreach ($product->products_attributes as $products_attribute) {
//          dump('here - 2');

          if ($products_attribute->field_name === $key) {
            $matchingValues = array_intersect($filterValues, (array)$products_attribute->value);
//            dump( $products_attribute->value);

          }
        }
        if (empty($matchingValues)) {
//          dump(dump('here - 4'));
          return false;
        }
      }
      return $product;
    });



//
//
//    foreach ($productsAllCollection as $product){
//      dump($product);
//    }
//    dd($productsAllCollection);


//    $page = Paginator::resolveCurrentPage('page');
    $perPage = 1;
//    $total = $productsAllCollection->count()/$perPage;

    $attributes = collect();

    foreach ($productsAllCollection as $product) {
      $attributes[] = $product->getProductAttributes();
    }

//    dump($attributes);

//    dump(request()->get('page'));

//    dump($productsAllCollection);

    $products = new LengthAwarePaginator($productsAllCollection->forPage(request()->get('page'), $perPage), $productsAllCollection->count(), $perPage, request()->get('page'), ['path'=>url()->current(),'pageName'=>'page']);

//    dd($products);

//    $products = $productsAllCollection->paginate(4);

    $maxPrice = $productsAllCollection->max('price');
    $minPrice = $productsAllCollection->min('price');
    $step = ceil(($maxPrice - $minPrice) / 4);

//dd($products);
//    dd($attributes->flatten()->unique('field_name'));



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

    $responseArray['attributes_count'] = $request->get('filters');

    $products = Product::query()
      ->whereIn('category_id', $categories)
      ->whereHas('attributes')
      ->whereHas('media')
      ->whereHas('prices', function ($query) {
        $query->where('type_id', DB::table('price_types')
          ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
          ->value('id'))
          ->where('price', '>', 0);
      })
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

    $responseArray['total_count'] = $selectedProducts->count();

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
        if (!array_key_exists('count', $responseArray['attributes_count'][$key_i][$key_j])) {
          $responseArray['attributes_count'][$key_i][$key_j]['count'] = 0;
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
    }

    if(empty($selectedFilterValues)){
      $responseArray['new_url'] = $newUrl;
    }
    else{
      $responseArray['new_url'] = $newUrl . '?' . http_build_query($selectedFilterValues);
    }

    return response()->json($responseArray);
  }

}
