<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBanner;
use App\Models\Setting;
use App\Observers\ProductObserver;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Product::observe(ProductObserver::class);

        /**
       * @var Setting $settings
       */
        $settings = Setting::query()->first();

        $products = Product::query()
            ->where('is_active', true)
            ->where('is_top_seller', true)
            ->whereHas('prices', function ($query) {
                $query->where('type_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                })->where('price', '>', 0);
            })->has('category')
            ->get();

        $instruments = Product::query()
            ->where('is_active', true)
            ->whereHas('prices', function ($query) {
                $query->where('type_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('price_types')
                        ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                })->where('price', '>', 0);
            })->whereHas('category', function (\Illuminate\Database\Eloquent\Builder $query) {
                    $query->whereJsonContains('slug->en', 'instrumenti-rozxidniki');
                })
            ->take(10)->get();

        $mainCategories = Category::query()
            ->whereNull('parent_id');

        $productCategories = $mainCategories
            ->with('children');

        $productBanners = ProductBanner::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        View::share([
            'settings' => $settings,
            'products' => $products,
            'instruments' => $instruments,
            'mainCategories' => $mainCategories->get(),
            'productCategories' => $productCategories->get(),
            'productBanners' => $productBanners,
        ]);
    }
}
