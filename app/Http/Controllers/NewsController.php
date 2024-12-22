<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Carbon\Carbon;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use function Doctrine\DBAL\Logging\query;

class NewsController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @return View
   */
  public function index(): View
  {
    $categories = NewsCategory::query()->with(['news' => function ($query) {
      $query->active();
    }])->get();
    return view('base.pages.news.index', [
      'categories' => $categories
    ]);
  }

  /**
   * Handle the incoming request.
   *
   * @return View
   */
  public function category(NewsCategory $news_category): View
  {
    $currentCategory = $news_category->load(['news' => function ($query) {
      $query->active();
    }]);

    $categories = NewsCategory::query()->with(['news' => function ($query) {
      $query->active();
    }])->get();

    return view('base.pages.news.index', [
      'categories' => $categories,
      'news_category' => $currentCategory
    ]);
  }


  /**
   * Handle the incoming request.
   *
   * @return View
   */
  public function show(NewsCategory $news_category, News $news): View
  {
    $categories = NewsCategory::query()->get();

    return view('base.pages.news.show', [
      'categories' => $categories,
      'news' => $news,
      'news_category' => $news_category
    ]);
  }


}
