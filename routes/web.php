<?php

// Nova Tool routes (должны быть в начале)
Route::get('/nova-tools/feed-generator', [App\Http\Controllers\Nova\FeedGeneratorController::class, 'index'])->name('nova.feed-generator');

// Feed routes (должны быть в начале)
Route::get('/feed/remarketing', [App\Http\Controllers\FeedController::class, 'remarketingAllCategories'])->name('feed.remarketing.all');
Route::get('/feed/remarketing/category', [App\Http\Controllers\FeedController::class, 'remarketingByCategory'])->name('feed.remarketing.category');
Route::get('/api/categories', [App\Http\Controllers\FeedController::class, 'getCategories'])->name('api.categories');

use App\Http\Controllers\Account\ChangePasswordController;
use App\Http\Controllers\Account\PersonalDataController;
use App\Http\Controllers\Account\UserAddressController;
use App\Http\Controllers\Account\ViewedProductsController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChangeThemeController;
use App\Http\Controllers\CommandRunnerController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FastOrderController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MoySkladBearerSyncController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NovaPoshtaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportAvailabilityController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SyncProductController;
use App\Http\Controllers\SyncProductImagesController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\WayForPayController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WishlistController;
use App\Models\Category;
use App\Models\PrivacyPolicy;
use App\Models\Product;
use App\Models\Redirect;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

require __DIR__.'/auth.php';

// Виклик розкладу (cron без SSH): налаштуйте на хості виклик URL кожну хвилину, напр. https://wrap.shop/run-scheduler?token=ВАШ_ТОКЕН
Route::get('/run-scheduler', function () {
    $token = env('SCHEDULER_TOKEN');
    $given = trim((string) request('token', ''));
    if ($token !== null && $token !== '' && $given !== trim((string) $token)) {
        abort(403, 'Invalid token');
    }
    try {
        Artisan::call('schedule:run');
        $output = trim(Artisan::output());
        Log::info('Cron: schedule:run виконано', [
            'at' => now()->toIso8601String(),
            'ip' => request()->ip(),
            'scheduler_output' => $output !== '' ? $output : '(немає рядків — жодна задача не була в черзі на цю хвилину)',
        ]);
        Log::info('Cron: /run-scheduler — OK, планувальник успішно відпрацював без помилок.');
    } catch (\Throwable $e) {
        Log::error('Cron: schedule:run помилка', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        throw $e;
    }

    return response()->json(['ok' => true, 'message' => 'Schedule run completed'], 200);
})->name('run-scheduler');

// SMTP ping без SSH: https://wrap.shop/mail-debug/ping?token=SCHEDULER_TOKEN
Route::get('/mail-debug/ping', function () {
    $token = env('SCHEDULER_TOKEN');
    $given = trim((string) request('token', ''));
    if ($token !== null && $token !== '' && $given !== trim((string) $token)) {
        abort(403, 'Invalid token');
    }

    $defaultHost = (string) env('MAIL_HOST', '');
    $defaultPort = (int) env('MAIL_PORT', 587);
    $defaultTimeout = (float) env('MAIL_TIMEOUT', 10);

    $host = trim((string) request('host', $defaultHost));
    $port = (int) request('port', $defaultPort);
    $timeout = (float) request('timeout', $defaultTimeout);
    if ($port < 1 || $port > 65535) {
        $port = $defaultPort;
    }
    if ($timeout < 1 || $timeout > 30) {
        $timeout = $defaultTimeout;
    }

    $ips = @gethostbynamel($host) ?: [];
    $target = sprintf('tcp://%s:%d', $host, $port);

    $errno = 0;
    $errstr = '';
    $startedAt = microtime(true);
    $stream = @stream_socket_client($target, $errno, $errstr, $timeout);
    $elapsedMs = (int) round((microtime(true) - $startedAt) * 1000);

    $connected = is_resource($stream);
    if ($connected) {
        fclose($stream);
    }

    return response()->json([
        'ok' => $connected,
        'mail' => [
            'host_default' => $defaultHost,
            'host' => $host,
            'port_default' => $defaultPort,
            'port' => $port,
            'encryption' => env('MAIL_ENCRYPTION'),
            'timeout' => $timeout,
        ],
        'dns_ips' => $ips,
        'connect' => [
            'target' => $target,
            'connected' => $connected,
            'errno' => $errno,
            'error' => $errstr,
            'elapsed_ms' => $elapsedMs,
        ],
    ], $connected ? 200 : 503);
})->name('mail-debug.ping');

// SMTP send-test без SSH: https://wrap.shop/mail-debug/send?token=SCHEDULER_TOKEN&to=you@example.com
Route::get('/mail-debug/send', function () {
    $token = env('SCHEDULER_TOKEN');
    $given = trim((string) request('token', ''));
    if ($token !== null && $token !== '' && $given !== trim((string) $token)) {
        abort(403, 'Invalid token');
    }

    $to = trim((string) request('to', env('MAIL_FROM_ADDRESS', '')));
    if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return response()->json([
            'ok' => false,
            'error' => 'Invalid or empty `to` email',
        ], 422);
    }

    $subject = 'SMTP test '.now()->format('Y-m-d H:i:s');
    $body = "SMTP test message from wrap.shop\n".'Host: '.env('MAIL_HOST').' Port: '.env('MAIL_PORT');

    try {
        \Illuminate\Support\Facades\Mail::raw($body, function ($m) use ($to, $subject) {
            $m->to($to)->subject($subject);
        });

        return response()->json([
            'ok' => true,
            'sent_to' => $to,
            'mail' => [
                'host' => env('MAIL_HOST'),
                'port' => (int) env('MAIL_PORT'),
                'encryption' => env('MAIL_ENCRYPTION'),
            ],
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'sent_to' => $to,
            'mail' => [
                'host' => env('MAIL_HOST'),
                'port' => (int) env('MAIL_PORT'),
                'encryption' => env('MAIL_ENCRYPTION'),
            ],
            'error' => $e->getMessage(),
        ], 503);
    }
})->name('mail-debug.send');

// Тест синхронізації цін і залишку одного товару через MOY_SKLAD_TOKEN (Bearer). Захист: ?token= той самий, що SCHEDULER_TOKEN (якщо заданий).
Route::get('/moysklad/bearer-sync-one-product', MoySkladBearerSyncController::class)->name('moysklad.bearer-sync-one-product');

/*
| Обробка черги без SSH: додайте в FASTPANEL другий cron (кожну хвилину), напр.:
| curl -sS "https://ваш-домен/run-queue?token=ТОЙ_САМИЙ_ЩО_SCHEDULER_TOKEN"
| Той самий SCHEDULER_TOKEN. Потрібно QUEUE_CONNECTION=database (або redis), не sync.
| Необов’язково: QUEUE_WORK_MAX_TIME (сек., за замовч. 55), QUEUE_WORK_MAX_JOBS (0 = без ліміту джоб за один запуск).
*/
Route::get('/run-queue', function () {
    $token = env('SCHEDULER_TOKEN');
    $given = trim((string) request('token', ''));
    if ($token !== null && $token !== '' && $given !== trim((string) $token)) {
        abort(403, 'Invalid token');
    }

    $connection = (string) config('queue.default', 'database');
    if ($connection === 'sync') {
        Log::info('Cron: /run-queue — OK (пропуск: QUEUE_CONNECTION=sync, окремий воркер не потрібен).', [
            'ip' => request()->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'QUEUE_CONNECTION=sync — воркер не потрібен, джоби виконуються в тому ж запиті.',
        ]);
    }

    $lock = Cache::lock('run-queue-http', 120);
    if (! $lock->get()) {
        Log::warning('Cron: /run-queue — пропуск: вже виконується інший запит (lock).', [
            'ip' => request()->ip(),
        ]);

        return response()->json([
            'ok' => false,
            'message' => 'run-queue вже виконується (інший запит/cron). Спробуйте через хвилину.',
        ], 429);
    }

    try {
        $maxTime = max(10, min(120, (int) env('QUEUE_WORK_MAX_TIME', 55)));
        $maxJobs = (int) env('QUEUE_WORK_MAX_JOBS', 0);

        $jobsTable = config('queue.connections.database.table', 'jobs');
        $jobsPendingBefore = null;
        if ($connection === 'database') {
            try {
                $jobsPendingBefore = DB::table($jobsTable)->count();
            } catch (\Throwable) {
                $jobsPendingBefore = null;
            }
        }

        $params = [
            'connection' => $connection,
            '--stop-when-empty' => true,
            '--max-time' => $maxTime,
            '--sleep' => 1,
        ];
        if ($maxJobs > 0) {
            $params['--max-jobs'] = $maxJobs;
        }

        Artisan::call('queue:work', $params);
        $output = trim(Artisan::output());

        $jobsPendingAfter = null;
        if ($connection === 'database') {
            try {
                $jobsPendingAfter = DB::table($jobsTable)->count();
            } catch (\Throwable) {
                $jobsPendingAfter = null;
            }
        }

        Log::info('Cron: queue:work через /run-queue — деталі виконання', [
            'at' => now()->toIso8601String(),
            'ip' => request()->ip(),
            'connection' => $connection,
            'max_time' => $maxTime,
            'jobs_in_queue_before' => $jobsPendingBefore,
            'jobs_in_queue_after' => $jobsPendingAfter,
            'worker_output' => $output !== '' ? $output : '(немає виводу воркера)',
        ]);
        Log::info('Cron: /run-queue — OK, обробник черги успішно відпрацював (завершився без помилки).', [
            'connection' => $connection,
            'jobs_left' => $jobsPendingAfter,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Queue worker stopped (empty or max-time)',
            'connection' => $connection,
            'max_time_sec' => $maxTime,
            'jobs_in_queue_before' => $jobsPendingBefore,
            'jobs_in_queue_after' => $jobsPendingAfter,
            'output' => $output,
        ]);
    } catch (\Throwable $e) {
        Log::error('Cron: /run-queue помилка', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        throw $e;
    } finally {
        $lock->release();
    }
})->name('run-queue');

Route::get('/admin/run-media', function () {
    try {
        $output = Artisan::call('media:generate-sync', [
            '--collection' => 'images',
            '--force' => true,
            '--only-missing' => true,
        ]);

        $result = Artisan::output();

        return response()->json([
            'success' => true,
            'message' => '✅ Конверсии пересозданы',
            'output' => $result,
            'exit_code' => $output,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

Route::get('/admin/run-migrations', function () {
    try {
        $exitCode = Artisan::call('migrate', [
            '--force' => true,
        ]);

        $output = Artisan::output();

        return response()->json([
            'success' => $exitCode === 0,
            'message' => $exitCode === 0
                ? 'Міграції успішно виконано'
                : 'Міграції завершилися з кодом '.$exitCode,
            'output' => $output,
            'exit_code' => $exitCode,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Помилка виконання міграцій: '.$e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

Route::get('/admin/run-migrations', function () {
    try {
        $exitCode = Artisan::call('migrate', [
            '--force' => true,
        ]);

        $output = Artisan::output();

        return response()->json([
            'success'   => $exitCode === 0,
            'message'   => $exitCode === 0
                ? 'Міграції успішно виконано'
                : 'Міграції завершилися з кодом ' . $exitCode,
            'output'    => $output,
            'exit_code' => $exitCode,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Помилка виконання міграцій: ' . $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
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
    Route::post('/sync-prices-stock', [CommandRunnerController::class, 'syncPricesAndStock']);
    Route::post('/sync-product-categories', [CommandRunnerController::class, 'syncProductCategories']);
});
Route::get('/slug-generate', function () {
    $products = Product::all();
    $categories = Category::all();
    foreach ($products as $product) {
        $product->slug = [
            'en' => Str::slug($product->getTranslation('name', 'en')),
            'uk' => Str::slug($product->getTranslation('name', 'uk')),
            'ru' => Str::slug($product->getTranslation('name', 'ru')),
        ];
        $product->save();
    }
    foreach ($categories as $category) {
        $category->slug = [
            'en' => Str::slug($category->getTranslation('name', 'en')),
            'uk' => Str::slug($category->getTranslation('name', 'uk')),
            'ru' => Str::slug($category->getTranslation('name', 'ru')),
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
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localizationRedirect', 'localeViewPath', 'themeMiddleware'],
], function () {

    Route::get('/api/get-cities', [NovaPoshtaController::class, 'getCities']);
    Route::get('/api/get-branches', [NovaPoshtaController::class, 'getBranches']);
    Route::get('/api/get-postmachines', [NovaPoshtaController::class, 'getPostMachines']);
    Route::get('/api/get-checkout-cart', [CartController::class, 'getCheckoutCart']);

    Route::get('/search', SearchController::class)->name('search');
    Route::post('/get-search-items', [SearchController::class, 'popupSearch'])->name('get-count');

    Route::get('/', IndexController::class)->name('index');

    Route::post('/get-count', [ProductController::class, 'getCount'])->name('get-count');


  Route::get('/privacy-policy', function (){
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

    Route::get('/videoreviews', [VideosController::class, 'index'])->name('videoreviews');

    Route::get('/videoreviews/{category}', [VideosController::class, 'show'])->name('videos.show');


    Route::group(['prefix' => '/news'], function(){

      Route::get('/', [NewsController::class, 'index'])->name('news.index');
      Route::get('/{news_category:slug}', [NewsController::class, 'category'])->name('news.category');
      Route::get('/{news_category:slug}/{news:slug}', [NewsController::class, 'show'])->name('news.show');
    });

    Route::group(['prefix' => '/blog'], function () {
        Route::get('/', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/author/{blog_author}', [BlogController::class, 'author'])->name('blog.author');
        Route::post('/{blog_post}/comments', [BlogCommentController::class, 'store'])
            ->middleware('throttle:15,1')
            ->name('blog.comments.store');
        Route::get('/{blog_post}', [BlogController::class, 'show'])->name('blog.show');
    });

  Route::get('/about-us', function () {
    return view('base.pages.about-us');
  })->name('about-us');

    Route::get('/implementations', function () {
        $implementations = \App\Models\Implementation::query()
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('base.pages.implementations', compact('implementations'));
    })->name('implementations');

    Route::get('/contacts', function () {
        return view('base.pages.contacts');
    })->name('contacts');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

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

    // Увесь каталог (усі категорії) — верхній рівень URL перед вкладеними /catalog/...
    Route::get('/catalog', [ProductController::class, 'catalog'])->name('products.catalog');

    // Категорії та SEO-сторінки фільтрів: /catalog/{path} щоб не перехоплювати /login, /cart, /api, /admin тощо
    Route::get('/catalog/{path}', [ProductController::class, 'categoryOrSeoFilter'])
        ->where('path', '[\p{L}\p{N}_\-/]+')
        ->name('products.category');

    // 301 редірект старих URL (/uk/plivki, /uk/plivki/kolir-chornyj) → /uk/catalog/... (SEO: зберегти індекс)
    // Має бути останнім у групі, щоб не перехоплювати /login, /cart, /account тощо
    Route::get('/{path}', function (string $path) {
        // Та сама логіка, що в RedirectMiddleware: якщо є запис у БД — одразу на to_url (без проміжного /catalog/)
        $normalized = Redirect::normalizePath(request()->getPathInfo());
        $dbRedirect = Redirect::findActiveForNormalizedPath($normalized);
        if ($dbRedirect !== null) {
            $target = $dbRedirect->to_url;
            if (! str_starts_with($target, 'http://') && ! str_starts_with($target, 'https://')) {
                $target = url($target);
            }
            if ($target !== request()->fullUrl()) {
                return redirect()->to($target, $dbRedirect->status_code);
            }
        }

        $url = route('products.category', ['path' => $path]);
        $query = request()->getQueryString();
        if ($query !== null && $query !== '') {
            $url .= '?'.$query;
        }

        return redirect()->to($url, 301);
    })->where('path', '[\p{L}\p{N}_\-/]+');
});
