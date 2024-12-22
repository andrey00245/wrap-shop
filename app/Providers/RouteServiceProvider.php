<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\UserAddress;
use App\Policies\UserAddressPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Gate::policy(UserAddress::class, UserAddressPolicy::class);

        Route::bind('category', function ($slug) {
          return Category::whereJsonContains('slug->en', $slug)->firstOrFail(); // Ищем по slug
        });
        Route::bind('subcategory', function ($slug) {
          return Category::whereJsonContains('slug->en', $slug)->firstOrFail(); // Ищем по slug
        });
        Route::bind('subsubcategory', function ($slug) {
          return Category::whereJsonContains('slug->en', $slug)->firstOrFail(); // Ищем по slug
        });
        Route::bind('news_category', function ($slug) {
          return NewsCategory::whereJsonContains('slug->en', $slug)->firstOrFail(); // Ищем по slug
        });
        Route::bind('news', function ($slug) {
          return News::whereJsonContains('slug->en', $slug)->firstOrFail(); // Ищем по slug
        });
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        $this->mapAuthRoutes();

        // Additional route files can be added here
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::prefix('auth')
            ->middleware('web')
            ->group(base_path('routes/auth.php'));
    }
}
