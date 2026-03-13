<?php

namespace App\Providers;

use App\Nova\Attribute;
use App\Nova\Banner;
use App\Nova\BestSeller;
use App\Nova\Category;
use App\Nova\Consultation;
use App\Nova\CustomBlock;
use App\Nova\DeliveryOption;
use App\Nova\Faq;
use App\Nova\Feedback;
use App\Nova\Implementation;
use App\Nova\News;
use App\Nova\NewsCategory;
use App\Nova\Order;
use App\Nova\PaymentOption;
use App\Nova\PriceType;
use App\Nova\PrivacyPolicy;
use App\Nova\Product;
use App\Nova\ProductBanner;
use App\Nova\ReportAvailability;
use App\Nova\Review;
use App\Nova\Setting;
use App\Nova\User;
use App\Nova\Video;
use App\Nova\VideoCategory;
use App\Nova\VideoReview;
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

        Nova::name('Wrap Shop');

        Nova::mainMenu(function (Request $request) {
            $user = $request->user();
            $role = $user?->role ?? 'user';

            // Базовые секции, общие для админа и контент‑менеджера
            $menu = [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),
            ];

            // Только админ: заказы и консультации
            if ($role === 'admin') {
                $menu[] = MenuSection::make('Замовлення', [
                    MenuItem::resource(Order::class),
                    MenuItem::resource(Consultation::class),
                ])->icon('shopping-bag')->collapsable();
            }

            // Товары
            if ($role === 'admin') {
                // Админ видит всё
                $menu[] = MenuSection::make('Товари', [
                    MenuItem::resource(Product::class),
                    MenuItem::resource(BestSeller::class),
                    MenuItem::resource(Attribute::class),
                    MenuItem::resource(Category::class),
                    MenuItem::resource(PriceType::class),
                    MenuItem::resource(Implementation::class),
                    MenuItem::resource(ReportAvailability::class),
                ])->icon('collection')->collapsable();
            } elseif ($role === 'content_manager') {
                // Контент‑менеджер: товары + категории, но без типов цен
                $menu[] = MenuSection::make('Товари', [
                    MenuItem::resource(Product::class),
                    MenuItem::resource(Category::class),
                ])->icon('collection')->collapsable();
            }

            // Пользователи / отзывы
            if ($role === 'admin') {
                $menu[] = MenuSection::make('Користувачі', [
                    MenuItem::resource(User::class),
                    MenuItem::resource(Review::class),
                ])->icon('user')->collapsable();
            } elseif ($role === 'content_manager') {
                // Контент‑менеджер видит только отзывы
                $menu[] = MenuSection::make('Користувачі', [
                    MenuItem::resource(Review::class),
                ])->icon('user')->collapsable();
            }

            // Контент (виды, «звуки», новини, FAQ тощо)
            if (in_array($role, ['admin', 'content_manager'], true)) {
                $menu[] = MenuSection::make('Контент', [
                    MenuItem::resource(Banner::class),
                    MenuItem::resource(ProductBanner::class),
                    MenuItem::resource(CustomBlock::class),
                    MenuItem::resource(NewsCategory::class),
                    MenuItem::resource(News::class),
                    MenuItem::resource(Faq::class),
                    MenuItem::resource(VideoCategory::class),
                    MenuItem::resource(VideoReview::class),
                ])->icon('document-text')->collapsable();
            }

            // Доставка та оплата – доступно и админу, и контент‑менеджеру
            if (in_array($role, ['admin', 'content_manager'], true)) {
                $menu[] = MenuSection::make('Доставка та оплата', [
                    MenuItem::resource(DeliveryOption::class),
                    MenuItem::resource(PaymentOption::class),
                ])->icon('truck')->collapsable();
            }

            // Ниже — только для админа
            if ($role === 'admin') {
                $menu[] = MenuSection::resource(PrivacyPolicy::class)->icon('shield-check');

                $menu[] = MenuSection::resource(Setting::class)->icon('cog');

                $menu[] = MenuSection::make('Команди', [
                    MenuItem::externalLink('Виконати команди', '/nova-vendor/command-runner'),
                ])->icon('terminal')->collapsable();

                $menu[] = MenuSection::make('Генератор фідів', [
                    MenuItem::externalLink('Генератор фідів', '/nova-tools/feed-generator'),
                ])->icon('document-text')->collapsable();
            }

            return $menu;
        });
		// Кастомный футер
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
            return in_array($user->role, ['admin', 'content_manager'], true);
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
			new \App\Nova\Tools\CommandRunner,
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
