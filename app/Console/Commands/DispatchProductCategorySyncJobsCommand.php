<?php

namespace App\Console\Commands;

use App\Jobs\SyncProductCategoriesFromMoySkladJob;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

/**
 * Ланцюжок джоб, як {@see DispatchProductPriceSyncJobsCommand}; розмір батчу за замовчуванням як у цін (SCHEDULE_PRICE_SYNC_BATCH).
 */
class DispatchProductCategorySyncJobsCommand extends Command
{
    protected $signature = 'products:dispatch-category-sync-jobs
                            {--batch= : Скільки товарів на одну джобу (1–500); за замовчуванням — schedule_price_sync_batch}';

    protected $description = 'Ставить у чергу ланцюжок SyncProductCategoriesFromMoySkladJob (лише category_id з МС)';

    public function handle(): int
    {
        $defaultBatch = (int) config('app.schedule_price_sync_batch', 100);
        $batchOpt = $this->option('batch');
        $batch = $batchOpt !== null && $batchOpt !== ''
            ? max(1, min(500, (int) $batchOpt))
            : max(1, min(500, $defaultBatch));

        $total = Product::query()->whereNotNull('external_id')->count();

        if ($total === 0) {
            $this->warn('Немає товарів з external_id.');

            return self::SUCCESS;
        }

        $chain = [];
        for ($offset = 0; $offset < $total; $offset += $batch) {
            $size = min($batch, $total - $offset);
            $chain[] = new SyncProductCategoriesFromMoySkladJob($offset, $size);
        }

        if ($chain === []) {
            return self::SUCCESS;
        }

        Bus::chain($chain)->dispatch();

        $jobs = count($chain);
        $this->info("Поставлено ланцюжок з {$jobs} джоб(и): усього {$total} товарів, батч {$batch}.");

        Log::info('products:dispatch-category-sync-jobs: ланцюжок відправлено в чергу', [
            'jobs_in_chain' => $jobs,
            'products_total' => $total,
            'batch' => $batch,
            'queue_connection' => config('queue.default'),
        ]);

        return self::SUCCESS;
    }
}
