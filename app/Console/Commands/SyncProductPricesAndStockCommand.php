<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Console\Command;

class SyncProductPricesAndStockCommand extends Command
{
    protected $signature = 'products:sync-prices-and-stock
                            {--chunk=100 : Кількість товарів за один внутрішній прохід (лише для повного синку)}
                            {--limit=0 : Ліміт товарів (0 = усі; у batch разом з offset — розмір батчу)}
                            {--offset=0 : Зміщення по id (для джоб — кожен батч зі своїм offset)}';

    protected $description = 'Оновлює ціни та залишки товарів з МойСклад для товарів з external_id';

    public function handle(ProductService $productService): int
    {
        set_time_limit(0);

        $offset = max(0, (int) $this->option('offset'));
        $limit = max(0, (int) $this->option('limit'));
        $chunkSize = max(1, min(500, (int) $this->option('chunk')));

        $batchMode = ($offset > 0 || $limit > 0);

        if ($batchMode) {
            return $this->runBatch($productService, $offset, $limit);
        }

        return $this->runFull($productService, $chunkSize, $limit);
    }

    /**
     * Повний синк (як раніше): усі товари, внутрішній chunk по БД.
     */
    private function runFull(ProductService $productService, int $chunkSize, int $limit): int
    {
        $this->info('Синхронізація цін та залишків з МС (повний режим)...');

        $query = Product::query()->whereNotNull('external_id');
        $total = $query->count();

        if ($total === 0) {
            $this->warn('Немає товарів з external_id.');

            return self::SUCCESS;
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $barMax = $limit > 0 ? min($limit, $total) : $total;
        $bar = $this->output->createProgressBar($barMax);
        $bar->start();

        $updated = 0;
        $failed = 0;

        $query->orderBy('id')->chunk($chunkSize, function ($products) use ($productService, &$updated, &$failed, $bar) {
            foreach ($products as $product) {
                [$u, $f] = $this->syncOne($productService, $product);
                $updated += $u;
                $failed += $f;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info("Готово. Оновлено: {$updated}, помилок/пропущено: {$failed}.");

        return self::SUCCESS;
    }

    /**
     * Один батч (offset + limit) — для черги; без chunk() по всьому каталогу.
     */
    private function runBatch(ProductService $productService, int $offset, int $limit): int
    {
        $this->info("Синхронізація цін/залишків (batch offset={$offset}, limit={$limit})...");

        $query = Product::query()->whereNotNull('external_id')->orderBy('id');

        if ($offset > 0) {
            $query->offset($offset);
        }
        if ($limit > 0) {
            $query->limit($limit);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $this->warn('У цьому діапазоні немає товарів.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $updated = 0;
        $failed = 0;

        foreach ($products as $product) {
            [$u, $f] = $this->syncOne($productService, $product);
            $updated += $u;
            $failed += $f;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Batch offset={$offset} готово. Оновлено: {$updated}, помилок/пропущено: {$failed}.");

        return self::SUCCESS;
    }

    /**
     * @return array{0: int, 1: int} [updated, failed]
     */
    private function syncOne(ProductService $productService, Product $product): array
    {
        try {
            if ($productService->syncProductPricesAndStock($product)) {
                return [1, 0];
            }

            return [0, 1];
        } catch (\Throwable $e) {
            $this->newLine();
            $this->error("Товар id={$product->id} ({$product->external_id}): ".$e->getMessage());

            return [0, 1];
        }
    }
}
