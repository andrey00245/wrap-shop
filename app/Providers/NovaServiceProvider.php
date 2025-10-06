<?php

namespace App\Providers;

use App\Nova\Attribute;
use App\Nova\Banner;
use App\Nova\BestSeller;
use App\Nova\Category;
use App\Nova\Consultation;
use App\Nova\CustomBlock;
use App\Nova\Faq;
use App\Nova\FastOrder;
use App\Nova\Feedback;
use App\Nova\Implementation;
use App\Nova\News;
use App\Nova\NewsCategory;
use App\Nova\Order;
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
		return [
			MenuSection::dashboard(Main::class)->icon('chart-bar'),

		MenuSection::make('Замовлення', [
			MenuItem::resource(Order::class),
			MenuItem::resource(Consultation::class),
			MenuItem::resource(FastOrder::class),
		])->icon('shopping-bag')->collapsable(),

			MenuSection::make('Товари', [
				MenuItem::resource(Product::class),
				MenuItem::resource(BestSeller::class),
				MenuItem::resource(Attribute::class),
				MenuItem::resource(Category::class),
				MenuItem::resource(PriceType::class),
				MenuItem::resource(Implementation::class),
				MenuItem::resource(ReportAvailability::class),
			])->icon('collection')->collapsable(),

			MenuSection::make('Користувачі', [
				MenuItem::resource(User::class),
				MenuItem::resource(Review::class),
			])->icon('user')->collapsable(),

			MenuSection::make('Контент', [
				MenuItem::resource(Banner::class),
				MenuItem::resource(ProductBanner::class),
				MenuItem::resource(CustomBlock::class),
				MenuItem::resource(NewsCategory::class),
				MenuItem::resource(News::class),
				MenuItem::resource(Faq::class),
				MenuItem::resource(VideoCategory::class),
				MenuItem::resource(VideoReview::class),
			])->icon('document-text')->collapsable(),

			MenuSection::resource(PrivacyPolicy::class)->icon('shield-check'),

			MenuSection::resource(Setting::class)->icon('cog'),

			MenuSection::make('Команди', [
				MenuItem::externalLink('Виконати команди', '/nova-vendor/command-runner'),
			])->icon('terminal')->collapsable(),

	];
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
			return in_array($user->email, [
				'test@gmail.com',
				'admin-wrap@gmail.com',
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
