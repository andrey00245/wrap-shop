<?php

namespace App\Console\Commands;

use App\Jobs\SyncPricesAndStockJob;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class DispatchProductPriceSyncJobsCommand extends Command
{
    protected $signature = 'products:dispatch-price-sync-jobs
                            {--batch=100 : Скільки товарів на одну джобу (1–500)}';

    protected $description = 'Ставить у чергу SyncPricesAndStockJob ланцюжком (послідовно), щоб не бити ліміти МойСклад';

    public function handle(): int
    {
        $batch = max(1, min(500, (int) $this->option('batch')));

        $total = Product::query()->whereNotNull('external_id')->count();

        if ($total === 0) {
            $this->warn('Немає товарів з external_id.');

            return self::SUCCESS;
        }

        $chain = [];
        for ($offset = 0; $offset < $total; $offset += $batch) {
            $size = min($batch, $total - $offset);
            $chain[] = new SyncPricesAndStockJob($offset, $size);
        }

        if ($chain === []) {
            return self::SUCCESS;
        }

        Bus::chain($chain)->dispatch();

        $jobs = count($chain);
        $this->info("Поставлено ланцюжок з {$jobs} джоб(и) (послідовно): усього {$total} товарів, батч {$batch}.");

        return self::SUCCESS;
    }
}
