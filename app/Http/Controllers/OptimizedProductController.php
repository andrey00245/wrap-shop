<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OptimizedProductController extends Controller
{
    /**
     * Optimized product index with caching
     */
    public function index(): View
    {
        $cacheKey = 'products_index_' . request()->get('page', 1);
        
        $data = Cache::remember($cacheKey, 3600, function () {
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
                ->with(['media', 'category', 'prices' => function ($query) {
                    $query->whereHas('type', function ($typeQuery) {
                        $typeQuery->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                    });
                }])
                ->paginate(6);

            $mainCategories = Category::query()
                ->whereNull('parent_id')
                ->select('id', 'name', 'slug')
                ->get();

            $categories = $products->take(5)->map(function ($product) {
                return $product->category;
            })->unique('id');

            return compact('categories', 'products', 'mainCategories');
        });

        return view('base.pages.products.index', $data);
    }

    /**
     * Optimized product show with caching
     */
    public function show(Product $product): View
    {
        $cacheKey = 'product_show_' . $product->id;
        
        $data = Cache::remember($cacheKey, 1800, function () use ($product) {
            $product->load([
                'media',
                'category',
                'products_attributes.attribute',
                'prices' => function ($query) {
                    $query->whereHas('type', function ($typeQuery) {
                        $typeQuery->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                    });
                }
            ]);

            // Related products with optimized query
            $relatedProducts = Product::query()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->whereHas('media')
                ->whereHas('prices', function ($query) {
                    $query->where('type_id', function ($subQuery) {
                        $subQuery->select('id')
                            ->from('price_types')
                            ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                    })->where('price', '>', 0);
                })
                ->with(['media'])
                ->take(4)
                ->get();

            return compact('product', 'relatedProducts');
        });

        return view('base.pages.products.show', $data);
    }

    /**
     * Optimized category products with caching
     */
    public function category(Category $category, Request $request): View
    {
        $cacheKey = 'category_products_' . $category->id . '_' . md5(serialize($request->all()));
        
        $data = Cache::remember($cacheKey, 1800, function () use ($category, $request) {
            $categories = collect([$category->id]);
            
            // Get subcategories
            $subcategories = Category::where('parent_id', $category->id)->pluck('id');
            $categories = $categories->merge($subcategories);
            
            // Get sub-subcategories
            $subSubcategories = Category::whereIn('parent_id', $subcategories)->pluck('id');
            $categories = $categories->merge($subSubcategories);

            $sortBy = $request->get('sort_by', 'id');
            $sortDirection = $request->get('sort_direction', 'desc');

            $productsQuery = Product::query()
                ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
                ->join('price_types', 'product_prices.type_id', '=', 'price_types.id')
                ->where('price_types.external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e')
                ->where('product_prices.price', '>', 0)
                ->whereIn('category_id', $categories)
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
                ->with(['media', 'category']);

            if ($request->get('in_stock')) {
                $productsQuery = $productsQuery->where('stock', '>', 0)
                    ->whereDoesntHave('attributes', function ($subQ) {
                        $subQ->where('field_name', 'under_order');
                    });
            }

            $products = $productsQuery->paginate(12);

            return compact('category', 'products');
        });

        return view('base.pages.products.category', $data);
    }
}
