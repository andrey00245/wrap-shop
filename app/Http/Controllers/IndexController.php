<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BestSeller;
use App\Models\Category;
use App\Models\CustomBlock;
use App\Models\Faq;
use App\Models\HomeBlock;
use App\Models\HomeBrand;
use App\Models\Implementation;
use App\Models\News;
use App\Models\Product;
use App\Models\Review;
use App\Models\YoutubeChannelCard;
use App\Support\HomeIndexCache;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();
        $payload = HomeIndexCache::remember($locale, function () {
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
                ->orderByInStockFirst()
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

            $homeBlocks = HomeBlock::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->with([
                    'items' => fn ($q) => $q
                        ->orderBy('sort_order')
                        ->with([
                            'category',
                            'product.category',
                            'product.media',
                            'media',
                            'quickLinks' => fn ($ql) => $ql
                                ->whereHas('category')
                                ->orderBy('sort_order')
                                ->with('category'),
                        ]),
                ])
                ->get();

            $homeReviewsLimit = 30;

            $baseReviewsQuery = Review::query()
                ->where('is_active', true)
                ->where('moderation_status', 'approved')
                ->with('product')
                ->latest();

            $homeReviewsWithProduct = (clone $baseReviewsQuery)
                ->whereNotNull('product_id')
                ->take($homeReviewsLimit)
                ->get();

            $remainingReviews = max(0, $homeReviewsLimit - $homeReviewsWithProduct->count());
            $homeReviewsWithoutProduct = $remainingReviews > 0
                ? (clone $baseReviewsQuery)
                    ->whereNull('product_id')
                    ->take($remainingReviews)
                    ->get()
                : collect();

            $homeReviews = $homeReviewsWithProduct
                ->concat($homeReviewsWithoutProduct)
                ->sortByDesc('created_at')
                ->values();

            $homeNews = News::query()
                ->active()
                ->whereHas('media')
                ->with(['category', 'media'])
                ->latest()
                ->take(10)
                ->get();

            $youtubeChannelCards = YoutubeChannelCard::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $homeFaqs = Faq::query()
                ->active()
                ->ordered()
                ->get();

            $homeBrands = HomeBrand::query()
                ->orderBy('sort_order')
                ->get();

            return compact(
                'topSellerCategories',
                'topSellersProducts',
                'latestCategory',
                'products',
                'banners',
                'exampleWorks',
                'customBlocks',
                'homeBlocks',
                'homeReviews',
                'homeNews',
                'youtubeChannelCards',
                'homeFaqs',
                'homeBrands'
            );
        });

        return view('base.pages.main', $payload);
    }
}
