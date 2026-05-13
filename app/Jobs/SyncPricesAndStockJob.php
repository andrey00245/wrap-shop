<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SyncPricesAndStockJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** Скільки разів повторити при падінні */
    public int $tries = 3;

    /** Таймаут джоби (секунди) */
    public int $timeout = 900;

    public int $offset;

    public int $batchLimit;

    public int $innerChunk;

    /**
     * @param  int  $offset  Зміщення по списку товарів (order by id)
     * @param  int  $batchLimit  Скільки товарів обробити в цьому батчі
     * @param  int  $innerChunk  Параметр --chunk для повного режиму (для batch не використовується)
     */
    public function __construct(int $offset = 0, int $batchLimit = 100, int $innerChunk = 100)
    {
        $this->offset = max(0, $offset);
        $this->batchLimit = max(1, min(500, $batchLimit));
        $this->innerChunk = max(1, min(500, $innerChunk));
    }

    /**
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

        Artisan::call('products:sync-prices-and-stock', [
            '--offset' => $this->offset,
            '--limit' => $this->batchLimit,
            '--chunk' => $this->innerChunk,
        ]);

        $output = trim(Artisan::output());
        Log::info('SyncPricesAndStockJob: batch виконано', [
            'offset' => $this->offset,
            'limit' => $this->batchLimit,
            'output' => $output !== '' ? $output : null,
        ]);
    }

    public function failed(?\Throwable $e): void
    {
        Log::error('SyncPricesAndStockJob: помилка батчу', [
            'offset' => $this->offset,
            'limit' => $this->batchLimit,
            'message' => $e?->getMessage(),
        ]);
    }
}
