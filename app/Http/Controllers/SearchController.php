<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SearchController extends Controller
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

    public function __invoke(Request $request): View
    {
        $selectedFilterValues = request()->all();

        $categories = Category::all();
        if (array_key_exists('search', $selectedFilterValues) && $selectedFilterValues['search'] === null) {
            return view('base.pages.products.search-base', [
                'categories' => $categories,
            ]);
        }

        if (!array_key_exists('search', $selectedFilterValues)) {
            return view('base.pages.products.search-base', [
                'categories' => $categories,
            ]);
        }
        $searchCategories = collect();

        if (request()->get('category_id')) {
            if (request()->get('category_id') === "0") {
                $searchCategories = Category::all()->pluck('id');
            } else {
                if (request()->get('sub_category') === "true") {

                    $searchCategories->push((int)request()->get('category_id'));
                    $searchCategory = Category::query()->where('id', request()->get('category_id'))
                        ->first()
                        ->getAllChildren()
                        ->pluck('id');
                    $searchCategories = $searchCategories->merge($searchCategory)->values();
                } else {
                    $searchCategories->push((int)request()->get('category_id'));
                }
            }
        }

        unset($selectedFilterValues['page'],
            $selectedFilterValues['sort_by'],
            $selectedFilterValues['sort_direction'],
            $selectedFilterValues['min_price'],
            $selectedFilterValues['max_price'],
            $selectedFilterValues['sub_category'],
            $selectedFilterValues['description'],
            $selectedFilterValues['category_id'],
            $selectedFilterValues['search']);

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
        $columns = ['name', 'code'];
        $searchValue = $request->get('search');
        $includeDescription = $request->boolean('description');
        if ($includeDescription) {
            $columns[] = 'descriptions';
        }


        $products = Product::query()
            ->when($searchValue, function ($query) use ($columns, $searchValue) {
                $query->whereLikeInsensitive($columns, $searchValue);
            })
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->join('price_types', 'product_prices.type_id', '=', 'price_types.id')
            ->when($searchCategories->isNotEmpty(), function ($query) use ($searchCategories) {
                return $query->whereIn('category_id', $searchCategories);
            })
            ->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
            ->where('product_prices.price', '>', 0)
            ->whereHas('media')
            ->select('products.*', DB::raw('MAX(product_prices.price) as price'))
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
                'products.stock',
                'products.banner_title')
            ->orderBy($sortBy, $sortDirection)
            ->with([
                'products_attributes' => function ($query) {
                    $query->join('attributes', 'products_attributes.attribute_id', '=', 'attributes.id');
                }
            ]);

        $productWithOutFilters = $products->get();

        $maxPrice = $productWithOutFilters->max('price') * Product::getCurrencyRate();
        $minPrice = $productWithOutFilters->min('price') * Product::getCurrencyRate();
        if (request()->get('min_price') && request()->get('max_price')) {
            $productWithFilters = $products
                ->where('product_prices.price', '>=', request()->get('min_price') / Product::getCurrencyRate())
                ->where('product_prices.price', '<=', request()->get('max_price') / Product::getCurrencyRate())
                ->get();
        } else {
            $productWithFilters = $productWithOutFilters;
        }

        $attributes = collect();

        foreach ($productWithOutFilters as $product) {
            $attributes[] = $product->getProductAttributes();
        }

        $productWithFilters = $productWithFilters->filter(function ($product) use ($selectedFilterValues) {
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
        $products = new LengthAwarePaginator($productWithFilters->forPage(request()->get('page'), $perPage), $productWithFilters->count(), $perPage, request()->get('page'), ['path' => url()->current(), 'pageName' => 'page']);
        $attributesArray = [];

        foreach ($attributes as $attribute) {
            foreach ($attribute as $item) {
                $attributesArray[$item->field_name][$item->pivot->value]['count'] = 0;
            }
        }

        if ($productWithOutFilters->count() === 0) {
            return view('base.pages.products.search-base', [
                'categories' => $categories,
            ]);
        }
        if ($searchCategories->isNotEmpty()) {
            $categories = $searchCategories;
        } else {
            $categories = $categories->pluck('id');
        }

        $responseArray = Product::getCountProducts($categories, $request, $selectedFilterValues, $attributesArray);
        return view('base.pages.products.search', [
            'products'      => $products,
            'minPrice'      => $minPrice,
            'maxPrice'      => $maxPrice,
            'attributes'    => $attributes->flatten()->unique('field_name') ?? collect(),
            'responseArray' => $responseArray,
        ]);
    }


    public function popupSearch(Request $request)
    {
        $columns = ['name', 'code'];

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
            ->whereLikeInsensitive($columns, $request->get('search'))
            ->get();

        return response()->json([
            'data' =>
                [
                    'view'        => view('base.components.search-product-list', [
                        'products' => $products->take(3),
                        'count'    => $products->count() - 3,
                    ])->render(),
                    'link'        => route('search', ['search' => $request->get('search')]),
                    'total_count' => $products->count(),
                ]
        ]);
    }
}
