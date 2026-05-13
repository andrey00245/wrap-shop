<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\VideoReview;
use Illuminate\View\View;
use function ZipStream\addDirectory;

class VideosController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return View
     */
    public function index(): View
    {
        $videos = VideoReview::query()->where('is_active',true)->latest()->get();

        $categories = VideoCategory::query()
            ->whereHas('videoReviews')
            ->get();

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
    public function show($id): View
    {
        $category = VideoCategory::findOrFail($id);

        $videos = VideoReview::query()
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->get();

        $categories = VideoCategory::query()
            ->whereHas('videoReviews')
            ->get();

        return view('base.pages.videoreviews', compact(
                'videos',
                'categories',
                'category'
            )
        );
    }
}
