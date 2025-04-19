<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot()
    {
        parent::boot();
        Route::bind('category', function ($slug) {
            return Category::whereJsonContains('slug->en', $slug)->firstOrFail();
        });
        Route::bind('subcategory', function ($slug) {
            return Category::whereJsonContains('slug->en', $slug)->firstOrFail();
        });
        Route::bind('subsubcategory', function ($slug) {
            return Category::whereJsonContains('slug->en', $slug)->firstOrFail();
        });
        Route::bind('news_category', function ($slug) {
            return NewsCategory::whereJsonContains('slug->en', $slug)->firstOrFail();
        });
        Route::bind('news', function ($slug) {
            return News::whereJsonContains('slug->en', $slug)->firstOrFail();
        });
    }

    public function map()
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
