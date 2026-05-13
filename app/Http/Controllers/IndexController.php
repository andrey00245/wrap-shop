<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BestSeller;
use App\Models\Category;
use App\Models\CustomBlock;
use App\Models\Implementation;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class IndexController extends Controller
{
    /** TTL кешу головної (секунди): коротко, щоб контент не відставав надовго після змін у адмінці */
    private const HOME_CACHE_TTL = 120;

    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();
        $cacheKey = 'home.index.v2.'.$locale;

        $payload = Cache::remember($cacheKey, self::HOME_CACHE_TTL, function () {
            $banners = Banner::query()
                ->where('is_active', true)
                ->orderBy('position')
                ->get();

            $customBlocks = CustomBlock::query()
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->with(['media', 'products.media', 'products.category'])
                ->get();

            $products = Product::query()
                ->where('is_active', 1)
                ->whereHas('media')
                ->whereHas('category')
                ->with(['media'])
                ->latest()
                ->whereHas('prices', function ($query) {
                    $query->where('type_id', function ($subQuery) {
                        $subQuery->select('id')
                            ->from('price_types')
                            ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                    })->where('price', '>', 0);
                })
                ->take(20)
                ->get();

            $topSellersProducts = BestSeller::query()
                ->whereHas('product.category')
                ->orderBy('sort_order', 'asc')
                ->with(['product.media', 'product.category'])
                ->get();

            $latestCategory = Category::query()
                ->whereHas('products', function ($query) use ($products) {
                    $query->whereIn('products.id', $products->pluck('id')->toArray());
                })
                ->get();

            $exampleWorks = Implementation::query()
                ->where('is_active', true)
                ->take(12)
                ->with(['product.media', 'product.category'])
                ->get();

            $topSellerCategories = $topSellersProducts->map(function ($bestSeller) {
                return $bestSeller->product->category;
            })->filter()->unique('id')->values();

            return compact(
                'topSellerCategories',
                'topSellersProducts',
                'latestCategory',
                'products',
                'banners',
                'exampleWorks',
                'customBlocks'
            );
        });

        return view('base.pages.main', $payload);
    }
}
