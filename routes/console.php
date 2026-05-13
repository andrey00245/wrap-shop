<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Синк цін/залишків з МС — ставиться з адмінки (без SSH), виконується при виклику schedule:run (cron або URL)
Schedule::call(function () {
    $pending = Cache::get('sync_prices_stock_pending');
    if (! $pending || ! is_array($pending)) {
        return;
    }
    Cache::forget('sync_prices_stock_pending');
    $chunk = (int) ($pending['chunk'] ?? 100);
    $limit = (int) ($pending['limit'] ?? 0);
    Log::info('Scheduler: ручний синк цін/залишків (з адмінки, sync_prices_stock_pending)', [
        'chunk' => $chunk,
        'limit' => $limit,
    ]);
    // limit=0 — усі товари: ланцюжок джоб (послідовно); інакше — один запуск з лімітом
    if ($limit === 0) {
        Artisan::call('products:dispatch-price-sync-jobs', [
            '--batch' => max(1, min(500, $chunk)),
        ]);
    } else {
        Artisan::call('products:sync-prices-and-stock', [
            '--chunk' => max(1, min(500, $chunk)),
            '--limit' => max(0, $limit),
        ]);
    }
    Log::info('Scheduler: ручний синк цін/залишків завершено', [
        'artisan_output' => trim(Artisan::output()),
    ]);
})->everyMinute()->name('sync_prices_stock')->withoutOverlapping(15);

// Ручний синк лише category_id з МС (з Command Runner); limit=0 — ланцюжок джоб, інакше один прохід з лімітом
Schedule::call(function () {
    $pending = Cache::get('sync_product_categories_pending');
    if (! $pending || ! is_array($pending)) {
        return;
    }
    Cache::forget('sync_product_categories_pending');
    $chunk = max(1, min(500, (int) ($pending['chunk'] ?? 100)));
    $limit = (int) ($pending['limit'] ?? 0);

    if ($limit === 0) {
        Artisan::call('products:dispatch-category-sync-jobs', [
            '--batch' => $chunk,
        ]);
    } else {
        Artisan::call('products:sync-categories-from-moysklad', [
            '--chunk' => $chunk,
            '--limit' => $limit,
        ]);
    }

    Log::info('Scheduler: ручний синк категорій товарів з МС (sync_product_categories_pending)', [
        'chunk' => $chunk,
        'limit' => $limit,
        'artisan_output' => trim(Artisan::output()),
    ]);
})->everyMinute()->name('sync_product_categories')->withoutOverlapping(30);

// 1 раз на добу — ланцюжок джоб SyncPricesAndStockJob (послідовно) + queue:work
Schedule::command('products:dispatch-price-sync-jobs', ['--batch' => (int) config('app.schedule_price_sync_batch', 100)])
    // 1 раз на добу (за замовчуванням о 03:00)
    ->dailyAt('03:00')
    ->name('dispatch_price_sync_jobs_daily')
    ->withoutOverlapping(10)
    ->before(function () {
        Log::info('Scheduler: старт products:dispatch-price-sync-jobs (1 раз на добу)');
    })
    ->onFailure(function () {
        Log::error('Scheduler: products:dispatch-price-sync-jobs упав. Перевірте деплой: app/Console/Commands/DispatchProductPriceSyncJobsCommand.php, app/Jobs/SyncPricesAndStockJob.php, php artisan optimize:clear');
    });

/*
 * Повне оновлення карток товарів з МойСклад (назви, атрибути тощо) — ставить у чергу джоби products:update.
 * Потрібен працюючий queue:work (QUEUE_CONNECTION не sync).
 * У .env: SCHEDULE_PRODUCTS_FULL_SYNC_END=15000 — верхня межа offset у МС (має покрити всі товари в кабінеті МС).
 * Нуль або відсутність змінної = не планувати.
 */
$fullSyncEnd = (int) env('SCHEDULE_PRODUCTS_FULL_SYNC_END', 0);
if ($fullSyncEnd > 0) {
    Schedule::command('products:update', ['0', (string) $fullSyncEnd])
        ->sundays()
        ->at('01:00')
        ->name('products_full_sync_weekly')
        ->withoutOverlapping(5)
        ->before(function () use ($fullSyncEnd) {
            Log::info('Scheduler: старт products:update (щотижневе повне оновлення)', [
                'end_offset' => $fullSyncEnd,
            ]);
        })
        ->after(function () {
            Log::info('Scheduler: команда products:update відпрацювала (джоби поставлені в чергу)');
        });
}
