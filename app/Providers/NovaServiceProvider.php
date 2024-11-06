<?php

namespace App\Providers;

use App\Nova\Attribute;
use App\Nova\Banner;
use App\Nova\Category;
use App\Nova\Feedback;
use App\Nova\Implementation;
use App\Nova\News;
use App\Nova\NewsCategory;
use App\Nova\Order;
use App\Nova\PriceType;
use App\Nova\Product;
use App\Nova\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),
                MenuSection::make('Користувачі', [
                    MenuItem::resource(User::class),
                    MenuItem::resource(Feedback::class),
                ])->icon('user')->collapsable(),

                MenuSection::make('Товари', [
                    MenuItem::resource(Product::class),
                    MenuItem::resource(Attribute::class),
                    MenuItem::resource(Category::class),
                    MenuItem::resource(PriceType::class),
                    MenuItem::resource(Order::class),
                    MenuItem::resource(Implementation::class),

                ])->icon('collection')->collapsable(),

                MenuSection::make('Банери', [
                    MenuItem::resource(Banner::class),
                ])->icon('clipboard')->collapsable(),

                MenuSection::make('Блог', [
                    MenuItem::resource(NewsCategory::class),
                    MenuItem::resource(News::class),
                ])->icon('desktop-computer')->collapsable(),
            ];
        });

        Nova::footer(function ($request) {
            return Blade::render('
                Wrap Shop
        ');
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ], true);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
