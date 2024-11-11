<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

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

        $mainCategories = Category::query()
            ->whereNull('parent_id')
            ->get();

        View::share([
            'products' => $products,
            'mainCategories' => $mainCategories,
        ]);
    }
}
