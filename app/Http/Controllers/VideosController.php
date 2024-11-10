<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\View\View;

class VideosController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return View
     */
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('is_top_seller', true)
            ->whereHas('prices', function ($query) {
                $query->where('type_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                })->where('price', '>', 0);
            })
            ->get();

        $videos = Video::query()->where('is_active',true)->latest()->get();
        $categories = VideoCategory::all();

        return view('base.pages.videoreviews', compact(
                'videos',
                'products',
                'categories',
            )
        );
    }

    /**
     * Handle the incoming request.
     *
     * @return View
     */
    public function show(VideoCategory $category): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('is_top_seller', true)
            ->whereHas('prices', function ($query) {
                $query->where('type_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                })->where('price', '>', 0);
            })
            ->get();

        $videos = Video::query()
            ->where('category_id', $category->id)
            ->where('is_active',true)
            ->latest()
            ->get();

        $categories = VideoCategory::all();

        return view('base.pages.videoreviews', compact(
                'videos',
                'products',
                'categories'
            )
        );
    }
}
