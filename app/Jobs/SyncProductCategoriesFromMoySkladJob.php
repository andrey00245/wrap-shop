<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SyncProductCategoriesFromMoySkladJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 900;

    public int $offset;

    public int $batchLimit;

    public function __construct(int $offset = 0, int $batchLimit = 100)
    {
        $this->offset = max(0, $offset);
        $this->batchLimit = max(1, min(500, $batchLimit));
    }

    /**
     * Той самий lock, що {@see SyncPricesAndStockJob}: не бити ліміти МС паралельними батчами (ціна vs категорія).
     *
     * @return array<int, WithoutOverlapping>
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('moysklad-sync-prices-stock'))
                ->releaseAfter(30)
                ->expireAfter(1200),
        ];
    }

    public function handle(): void
    {
        set_time_limit(0);

        Artisan::call('products:sync-categories-from-moysklad', [
            '--offset' => $this->offset,
            '--limit' => $this->batchLimit,
        ]);

        $output = trim(Artisan::output());
        Log::info('SyncProductCategoriesFromMoySkladJob: batch виконано', [
            'offset' => $this->offset,
            'limit' => $this->batchLimit,
            'output' => $output !== '' ? $output : null,
        ]);
    }

    public function failed(?\Throwable $e): void
    {
        Log::error('SyncProductCategoriesFromMoySkladJob: помилка батчу', [
            'offset' => $this->offset,
            'limit' => $this->batchLimit,
            'message' => $e?->getMessage(),
        ]);
    }
}
