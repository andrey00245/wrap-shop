<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Console\Command;

/**
 * Частота та 429: усі GET до remap йдуть через {@see \App\Services\MoySkladRemapHttp}
 * (пауза між запитами, ретраї 429) — див. config('app.moysklad_remap'), env MOY_SKLAD_REMAP_DELAY_MS.
 * Додаткових sleep тут немає, як у {@see SyncProductPricesAndStockCommand}.
 */
class SyncProductCategoriesFromMoySkladCommand extends Command
{
    protected $signature = 'products:sync-categories-from-moysklad
                            {--chunk=100 : Кількість товарів за один прохід по БД}
                            {--limit=0 : Ліміт товарів (0 = усі з external_id)}
                            {--offset=0 : Зміщення по id (разом з limit — один батч)}
                            {--product= : Лише один товар за внутрішнім id (ігнорує limit/offset)}';

    protected $description = 'Оновлює лише category_id з атрибута «Категорія сайту» в МойСклад (без повного синку картки)';

    public function handle(ProductService $productService): int
    {
        set_time_limit(0);

        $productId = $this->option('product');
        if ($productId !== null && $productId !== '') {
            return $this->runSingle($productService, (int) $productId);
        }

        $offset = max(0, (int) $this->option('offset'));
        $limit = max(0, (int) $this->option('limit'));
        $chunkSize = max(1, min(500, (int) $this->option('chunk')));

        $batchMode = ($offset > 0 || $limit > 0);

        if ($batchMode) {
            return $this->runBatch($productService, $offset, $limit);
        }

        return $this->runFull($productService, $chunkSize, $limit);
    }

    private function runSingle(ProductService $productService, int $productId): int
    {
        $product = Product::query()->find($productId);
        if (! $product) {
            $this->error("Товар id={$productId} не знайдено.");

            return self::FAILURE;
        }
        if (empty($product->external_id)) {
            $this->warn('У товару немає external_id.');

            return self::FAILURE;
        }
        $ok = $productService->syncProductSiteCategoryFromMoySklad($product);
        $this->info($ok ? 'Категорію оновлено з МС.' : 'Не вдалося оновити (немає даних у МС або атрибута).');

        return $ok ? self::SUCCESS : self::FAILURE;
    }

    private function runFull(ProductService $productService, int $chunkSize, int $limit): int
    {
        $this->info('Синхронізація категорій з МС (лише category_id)...');

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
        $this->info("Готово. Успішно: {$updated}, без зміни/помилка: {$failed}.");

        return self::SUCCESS;
    }

    private function runBatch(ProductService $productService, int $offset, int $limit): int
    {
        $this->info("Синхронізація категорій (batch offset={$offset}, limit={$limit})...");

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
        $this->info("Batch готово. Успішно: {$updated}, без зміни/помилка: {$failed}.");

        return self::SUCCESS;
    }

    /**
     * @return array{0: int, 1: int} [updated, failed]
     */
    private function syncOne(ProductService $productService, Product $product): array
    {
        try {
            if ($productService->syncProductSiteCategoryFromMoySklad($product)) {
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
