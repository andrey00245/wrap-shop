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
        $videos = Video::query()->where('is_active',true)->latest()->get();
        $categories = VideoCategory::all();

        return view('base.pages.videoreviews', compact(
                'videos',
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
        $videos = Video::query()
            ->where('category_id', $category->id)
            ->where('is_active',true)
            ->latest()
            ->get();

        $categories = VideoCategory::all();

        return view('base.pages.videoreviews', compact(
                'videos',
                'categories'
            )
        );
    }
}
