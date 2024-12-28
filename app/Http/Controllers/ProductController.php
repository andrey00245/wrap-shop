<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Implementation;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
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
      ->orderBy($sortBy, $sortDirection);

    $productsAllCollection = $products->get();
    $products = $products->paginate(4);

    $maxPrice = $productsAllCollection->max('price');
    $minPrice = $productsAllCollection->min('price');
    $step = ceil(($maxPrice - $minPrice) / 4);

    $attributes = collect();

    foreach ($products as $product) {
      $attributes[] = $product->getProductAttributes();
    }

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
    $filterTypes = [];
    $filterValues = [];

    foreach ($request->get('filters') as $key => $filterType) {
      $filterTypes[] = $key;
      foreach ($filterType as $key_i => $filterValue) {
//        dump($filterValue);
        if (in_array('true', $filterValue, true)) {
          $filterValues[$key][] = $key_i;
        }
      }
    }



    dump($request->get('filters'));
    dump($filterTypes);
    dd($filterValues);

//
//    dump($filterValues);
//
//
//    dd($filterTypes);

    $responseArray = $request->get('filters');

//    dd($responseArray);

    $attributes = Attribute::query()
      ->whereIn('field_name', $filterTypes)
      ->get();


//dump($filterValues);

    $products = Product::query()
      ->whereIn('category_id', [2])
      ->whereHas('attributes')
      ->whereHas('media')
      ->whereHas('prices', function ($query) {
        $query->where('type_id', DB::table('price_types')
          ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
          ->value('id'))
          ->where('price', '>', 0);
      })->with(['products_attributes' => function ($query) use ($filterValues) {
        $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id')
          ->where(function ($query) use ($filterValues) {
            foreach ($filterValues as $field_name => $filterValuesArray) {
              if (array_key_exists($field_name, $filterValues)) {
                $query->orWhereIn('attributes.field_name', [$field_name])
                  ->whereIn('products_attributes.value->'.App::getLocale(), $filterValuesArray);
              }
            }
          });
      }])
      ->get();


    $products = $products->filter(function ($product) use ($filterValues) {
      if ($product->products_attributes->count() === count($filterValues)) {
        return $product;
      }
      return false;
    });
//    dd($products);


//    dd($products);

    $testAttribute = ProductAttribute::query()->whereNotIn('attribute_id', [1,3,4,5,14])->pluck('value')->unique();

//dd($products->pluck('id'));

    $testArr = Attribute::query()->whereIn('field_name',[
      'brand',
      'material',
      'structure',
      'operating_temperature',
      'width',
      'application_method',
      'surface_temperature',
      'main_shade',
      'country_manufacture',
      'type',
      'form_release',
      'volume'
    ])->select(['field_name', 'id'])->get()->toArray();

    $result = [];
//    dd();

//    dd($testArr);

    foreach ($testArr as $item) {
      // Если ключ field_name уже существует в результирующем массиве
      if (isset($result[$item['field_name']])) {
        // Добавляем новый элемент в соответствующий массив
        $result[$item['field_name']][] = $item;
      } else {
        // Если нет, создаем новый ключ с массивом
        $result[$item['field_name']] = $item;
      }
    }

//    dump($result);
//    dump($testArr);
//    dd(array_intersect_key($result, $filterValues));

    $testAttribute = $testAttribute->map(function ($attribute) use ($products, $result, $filterValues) {
//      dd($attribute);
      foreach ($result as $key => $value) {
        if (array_key_exists($key, $filterValues)) {
          $productIds = $products->pluck('id')->toArray();
          break;
        } else {
          $productIds = ProductAttribute::query()
//            dd(array_key_exists($key, $filterValues), $key, $value, $filterValues)
//            ->where('attribute_id', $value['id'])
            ->whereIn('product_id',$products->pluck('id'))
            ->whereJsonContains('value->'.App::getLocale(), $attribute)
            ->pluck('product_id')->toArray();
            break;
        }
      }

      return [
        'name'=>$attribute,
        'count' => Product::query()
          ->whereIn('id', $productIds)
          ->whereHas('media')
          ->whereIn('category_id', [2])->count()
        ];
    });


//    dd($testAttribute);

//    $productsCount = Product::query()
//      ->whereIn('id', $productIds)
//      ->whereHas('media')
//      ->whereIn('category_id', $category->isParent() ? $category->children()->pluck('id') : [$category->id])
//      ->whereHas('prices', function ($query) {
//        $query->where('type_id', function ($subQuery) {
//          $subQuery->select('id')
//            ->from('price_types')
//            ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
//        })->where('price', '>', 0);
//      })->count();








//    $products = Product::query()
//      ->whereIn('category_id', [2])
//      ->whereHas('attributes'
//        function ($query) use ($filterValues){
//        $tempVar = [];
//        foreach ($filterValues as $key => $filterValue){
//          $tempVar[] = $key;
//        }
//        return $query->whereIn('field_name', $tempVar);
//      )
//      ->whereHas('media')
//      ->with('products_attributes', function ($query) use ($filterValues) {
//        $tempArr = [];

//        dd($filterValues);


//        dd($tempArr);
//        return $query->where('value->' . App::getLocale(), function ($queryQuery) use ($filterValues){
//
//
//          foreach ($filterValues as $key => $filterValue){
//            foreach ($filterValue as $filter){
//              $tempArr[] = $filter;
//            }
//          }
//        });
//      })
//      ->get();

//    dd($products);

//
//    $users = User::where('column1', '=', value1)
//      ->where(function($query) use ($variable1,$variable2){
//        $query->where('column2','=',$variable1)
//          ->orWhere('column3','=',$variable2);
//      })





//    dd($products);


//    foreach ($attributes as $attribute) {
//      dump($attribute);

//      $products = $attribute->productsVisible(2);

//      dump($products->get());
//      dump($products->wherePivot('value->uk', '3M')->get());
//      foreach ($products as $product) {
//        dump($product->pivot->value);

//      }
//    }
//    dd(1);

//    dd($testAttribute);


    foreach ($responseArray as $key_i => $attributes){
      foreach ($attributes as $key_j => $attribute){
        $responseArray[$key_i][$key_j]['count'] = $testAttribute->where('name', $key_j)->first()['count'];
//        dump($testAttribute->where('name', $key_j)->first()['count']);

      }
    }


//    foreach ($attributes as $attribute) {
//      foreach ($attribute->productsVisible(2)->get() as $product) {
//        $key = json_decode($product->pivot->value, true, 512, JSON_THROW_ON_ERROR)[App::getLocale()];
//        if (!array_key_exists('count', $responseArray[$attribute->field_name][$key])) {
//          $responseArray[$attribute->field_name][$key]['count'] = 0;
//        }
//        if (isset($responseArray[$attribute->field_name][$key]["count"])) {
//          ++$responseArray[$attribute->field_name][$key]['count'];
//        }
//      }
//    }

//    dd($responseArray);

    return response()->json([$responseArray]);
  }

}
