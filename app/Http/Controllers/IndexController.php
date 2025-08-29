<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BestSeller;
use App\Models\Category;
use App\Models\CustomBlock;
use App\Models\Implementation;
use App\Models\Product;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return View
     */
    public function __invoke(): View
    {
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        $customBlocks = CustomBlock::query()->where('is_active',true)
            ->orderBy('sort_order','asc')->get();

        $products = Product::query()
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
            ->get();

        $latestCategory = Category::query()
            ->whereHas('products', function ($query) use ($products) {
                $query->whereIn('products.id', $products->pluck('id')->toArray());
            })
            ->get();

        $exampleWorks = Implementation::query()->where('is_active',true)->take(9)->get();

        $topSellerCategories = Category::query()->whereHas('products', function ($qury) {
            $qury->where('products.is_top_seller', true);
        })->get();

        return view('base.pages.main', compact(
                'topSellerCategories',
                'topSellersProducts',
                'latestCategory',
                'products',
                'banners',
                'exampleWorks',
                'customBlocks'
            )
        );
    }
}
