<?php

namespace App\Providers;

use App\Models\BestSeller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBanner;
use App\Models\Setting;
use App\Observers\MediaObserver;
use App\Observers\ProductObserver;
use App\Translation\SafeFileLoader;
use Firebase\JWT\JWT;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('translation.loader', function ($loader, $app) {
            return new SafeFileLoader($app['files'], $app['path.lang']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Подавляем PHP Notice/Warning для Broken pipe через error handler
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            // Подавляем ошибки Broken pipe
            if (str_contains($errstr, 'file_put_contents') &&
                (str_contains($errstr, 'Broken pipe') ||
                 str_contains($errstr, 'errno=32'))) {
                return true; // Подавляем ошибку
            }

            // Подавляем ошибки из server.php
            if (str_contains($errfile, 'server.php') &&
                (str_contains($errstr, 'Broken pipe') ||
                 str_contains($errstr, 'errno=32'))) {
                return true; // Подавляем ошибку
            }

            // Возвращаем false для других ошибок, чтобы они обрабатывались стандартным образом
            return false;
        }, E_WARNING | E_NOTICE);

        $privateKey = file_get_contents(storage_path('AuthKey_'.env('APPLE_KEY_ID').'.p8'));

        $payload = [
            'iss' => env('APPLE_TEAM_ID'),
            'iat' => time(),
            'exp' => time() + 86400 * 180,
            'aud' => 'https://appleid.apple.com',
            'sub' => env('APPLE_CLIENT_ID'),
        ];

        $clientSecret = JWT::encode($payload, $privateKey, 'ES256', env('APPLE_KEY_ID'));

        Config::set('services.apple.client_secret', $clientSecret);

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Product::observe(ProductObserver::class);
        Media::observe(MediaObserver::class);

        /**
         * @var Setting $settings
         */
        $settings = Setting::query()->first();

        // Получаем продукты-лидеры продаж из новой таблицы best_sellers
        $products = BestSeller::query()
            ->whereHas('product', function ($query) {
                $query->where('is_active', true)
                    ->whereHas('prices', function ($priceQuery) {
                        $priceQuery->where('type_id', function ($subQuery) {
                            $subQuery->select('id')
                                ->from('price_types')
                                ->where('external_id', 'bb2a9a14-26f6-11ee-0a80-0f50000d072e');
                        })->where('price', '>', 0);
                    })
                    ->has('category');
            })
            ->with('product')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->pluck('product')
            ->filter()
            ->sort(function (?Product $a, ?Product $b): int {
                if ($a === null || $b === null) {
                    return 0;
                }
                $sa = $a->stock > 0 ? 0 : 1;
                $sb = $b->stock > 0 ? 0 : 1;
                if ($sa !== $sb) {
                    return $sa <=> $sb;
                }

                return $a->id <=> $b->id;
            })
            ->values();

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
            ->orderByInStockFirst()
            ->orderByDesc('id')
            ->take(10)->get();

        // Окремі запити: один builder з ->with() мутував би обидва варіанти
        $mainCategories = Category::query()
            ->whereNull('parent_id')
            ->get();

        $productCategories = Category::query()
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $productBanners = ProductBanner::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        View::share([
            'settings' => $settings,
            'products' => $products,
            'instruments' => $instruments,
            'mainCategories' => $mainCategories,
            'productCategories' => $productCategories,
            'productBanners' => $productBanners,
        ]);
    }
}
