<?php

use App\Http\Controllers\Account\ChangePasswordController;
use App\Http\Controllers\Account\PersonalDataController;
use App\Http\Controllers\Account\UserAddressController;
use App\Http\Controllers\Account\ViewedProductsController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChangeThemeController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\FastOrderController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NovaPoshtaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportAvailabilityController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SyncProductImagesController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\WayForPayController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\FaqController;
use App\Models\Category;
use App\Models\PrivacyPolicy;
use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\SyncProductController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CommandRunnerController;
use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/auth.php';

Route::get('/admin/run-media', function () {
    try {
        $output = Artisan::call('media:generate-sync', [
            '--collection'   => 'images',
            '--force'        => true,
            '--only-missing' => true,
        ]);
        
        $result = Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => '✅ Конверсии пересозданы',
            'output' => $result,
            'exit_code' => $output
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::match(['get', 'post'], 'login/apple/callback', [SocialController::class, 'handleAppleCallback'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('checkout/wayforpay/callback', [WayForPayController::class, 'callback'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('wayforpay.callback');

Route::get('/syn-images', [SyncProductImagesController::class, 'updateProducts']);
Route::get('/syn-products', [SyncProductController::class, 'updateProducts']);

// Webhook для МойСклад
Route::any('/webhook/moysklad', [WebhookController::class, 'handle'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('webhook.moysklad');
Route::middleware(['nova'])->prefix('nova-vendor/command-runner')->group(function () {
    Route::get('/', function () {
        return view('command-runner');
    });
    // Storage symlink (safe, behind Nova middleware)
    Route::post('/storage-link', [CommandRunnerController::class, 'storageLink']);
    Route::post('/sitemap', [CommandRunnerController::class, 'generateSitemap']);
    Route::post('/products', [CommandRunnerController::class, 'updateProducts']);
    Route::post('/cache', [CommandRunnerController::class, 'clearCache']);
    Route::post('/media', [CommandRunnerController::class, 'cleanMedia']);
    Route::post('/webhook/check', [CommandRunnerController::class, 'webhookCheck']);
    Route::post('/webhook/test', [CommandRunnerController::class, 'webhookTest']);
    Route::post('/webhook/create', [CommandRunnerController::class, 'webhookCreate']);
    Route::post('/custom-command', [CommandRunnerController::class, 'runCustomCommand']);
});
Route::get('/slug-generate', function () {
    $products = Product::all();
    $categories = Category::all();
    foreach ($products as $product) {
        $product->slug = [
            'en' => Str::slug($product->getTranslation('name', 'en')),
            'uk' => Str::slug($product->getTranslation('name', 'uk')),
            'ru' => Str::slug($product->getTranslation('name', 'ru'))
        ];
        $product->save();
    }
    foreach ($categories as $category) {
        $category->slug = [
            'en' => Str::slug($category->getTranslation('name', 'en')),
            'uk' => Str::slug($category->getTranslation('name', 'uk')),
            'ru' => Str::slug($category->getTranslation('name', 'ru'))
        ];
        $category->save();
    }

    return 'okay';
});

Route::match(['get', 'post'], 'checkout/wayforpay/success', [WayForPayController::class, 'success'])->name('wayforpay.success')
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::post('/add-product-to-wishlist', [WishlistController::class, 'update'])->name('add-product-to-wishlist');
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/remove/{productId}', [CartController::class, 'remove']);
Route::post('/cart/update/', [CartController::class, 'update']);
Route::get('/cart', [CartController::class, 'index']);
Route::post('/consultation', [ConsultationController::class, 'store']);
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::post('/report-availability', [ReportAvailabilityController::class, 'store'])->name('report.availability');

Route::post('/fast-order', [FastOrderController::class, 'store'])->name('fast-order.store');

Route::post('/change-theme', ChangeThemeController::class)->name('change-theme');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');


// Роуты видеоревью выносим из группы локализации
Route::get('/videoreviews', [VideosController::class, 'index'])->name('videoreviews')->middleware('themeMiddleware');
Route::get('/videoreviews/category/{id}', [VideosController::class, 'show'])->name('videos.show')->middleware('themeMiddleware');


Route::group([
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localizationRedirect', 'localeViewPath', 'themeMiddleware']
], function () {

    Route::get('/api/get-cities', [NovaPoshtaController::class, 'getCities']);
    Route::get('/api/get-branches', [NovaPoshtaController::class, 'getBranches']);
    Route::get('/api/get-postmachines', [NovaPoshtaController::class, 'getPostMachines']);
    Route::get('/api/get-checkout-cart', [CartController::class, 'getCheckoutCart']);

    Route::get('/search', SearchController::class)->name('search');
    Route::post('/get-search-items', [SearchController::class, 'popupSearch'])->name('get-count');


    Route::get('/', IndexController::class)->name('index');

    Route::post('/get-count', [ProductController::class, 'getCount'])->name('get-count');


    Route::get('/privacy-policy', function () {
        $privacy_policy = PrivacyPolicy::first();
        return view('base.pages.privacy-policy', compact('privacy_policy'));
    })->name('privacy-policy');

    Route::get('/checkout', function () {
        return view('base.pages.checkout.index');
    })->name('checkout');

    Route::get('/checkout/success', function () {
        return view('base.pages.checkout.success');
    })->name('checkout.success');

    Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe');

    Route::get('/shipping-and-payment', function () {
        return view('base.pages.delivery');
    })->name('delivery');


    Route::group(['prefix' => '/news'], function () {

        Route::get('/', [NewsController::class, 'index'])->name('news.index');
        Route::get('/{news_category:slug}', [NewsController::class, 'category'])->name('news.category');
        Route::get('/{news_category:slug}/{news:slug}', [NewsController::class, 'show'])->name('news.show');
    });


    Route::get('/about-us', function () {
        return view('base.pages.about-us');
    })->name('about-us');

    Route::get('/contacts', function () {
        return view('base.pages.contacts');
    })->name('contacts');

    Route::get('/faq', [FaqController::class, 'index'])->name('faq');

    Route::group(['prefix' => '/account', 'middleware' => ['redirect_if_not_authenticated']], function () {
        Route::get('/', function () {
            return view('base.pages.account.account');
        })->name('account');

        Route::group(['prefix' => '/personal-data'], function () {
            Route::get('/', function () {
                return view('base.pages.account.personal-data');
            })->name('personal-data.edit');
            Route::put('/update', [PersonalDataController::class, 'update'])->name('personal-data.update');
        });

        Route::group(['prefix' => '/change-password'], function () {
            Route::get('/', function () {
                return view('base.pages.account.change-password');
            })->name('change-password.edit');
            Route::patch('/update', [ChangePasswordController::class, 'update'])->name('change-password.update');
        });

        Route::group(['prefix' => '/address'], function () {
            Route::get('/', function () {
                return view('base.pages.account.address.index');
            })->name('address');
            Route::get('/create', [UserAddressController::class, 'create'])->name('account.address.create');
            Route::post('/store', [UserAddressController::class, 'store'])->name('account.address.store');
            Route::get('/edit/{address}', [UserAddressController::class, 'edit'])->name('account.address.edit');
            Route::put('/update/{address}', [UserAddressController::class, 'update'])->name('account.address.update');
            Route::delete('/delete/{address}', [UserAddressController::class, 'delete'])->name('account.address.delete');
        });

        Route::get('/order', function () {
            return view('base.pages.account.order');
        })->name('order');

        Route::get('/viewed-products', [ViewedProductsController::class, 'index'])->name('viewed-products');

        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    })->middleware('redirect_if_not_authenticated');

    Route::get('/wishlist/{product}/delete', [WishlistController::class, 'delete'])->name('wishlist.delete');


    Route::get('/restore-password', function () {
        return view('base.pages.account.restore-password');
    })->name('restore-password');


    Route::group(['prefix' => '/products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/{product}/show', [ProductController::class, 'show'])->name('products.show');
    });
    Route::get('/{category}/{subcategory?}/{subsubcategory?}', [ProductController::class, 'category'])->name('products.category');
});

