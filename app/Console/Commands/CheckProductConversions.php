<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Jobs\ProcessProductImages;
use Illuminate\Console\Command;

class CheckProductConversions extends Command
{
    protected $signature = 'products:check-conversions {--limit=10 : Количество продуктов для проверки} {--force : Принудительно пересоздать конверсии}';
    protected $description = 'Проверяет и создает конверсии для изображений продуктов';

    public function handle()
    {
        $limit = $this->option('limit');
        $force = $this->option('force');

        $this->info("🔍 Проверка конверсий для продуктов (лимит: {$limit})");

        $products = Product::whereNotNull('external_id')
            ->whereHas('media')
            ->take($limit)
            ->get();

        if ($products->isEmpty()) {
            $this->warn('Продукты с изображениями не найдены');
            return 0;
        }

        $this->info("Найдено продуктов с изображениями: {$products->count()}");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $processed = 0;
        $errors = 0;

        foreach ($products as $product) {
            try {
                // Запускаем джобу для проверки конверсий
                ProcessProductImages::dispatch($product);
                $processed++;
            } catch (\Exception $e) {
                $this->error("\nОшибка обработки продукта {$product->external_id}: " . $e->getMessage());
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info("\n\n✅ Обработка завершена!");
        $this->info("Обработано: {$processed}");
        $this->info("Ошибок: {$errors}");

        if ($force) {
            $this->info("\n💡 Для принудительного пересоздания конверсий используйте:");
            $this->line("php artisan media:generate-sync --collection=images --force");
        }

        return 0;
    }
}
