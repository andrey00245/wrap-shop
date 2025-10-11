<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Jobs\ProcessProductImages;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\File;

class RecreateProductMedia extends Command
{
    protected $signature = 'products:recreate-media {--limit=10 : Количество продуктов для обработки}';
    protected $description = 'Удаляет всю медиа продуктов и пересоздает заново с конверсиями';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("🔄 Пересоздание медиа для продуктов (лимит: {$limit})");

        // 1. Удаляем ВСЮ медиа продуктов из БД и файлы
        $this->info("🗑️ Удаление существующей медиа...");
        $this->deleteAllProductMedia();

        // 2. Получаем продукты для обработки
        $products = Product::whereNotNull('external_id')
            ->take($limit)
            ->get();

        if ($products->isEmpty()) {
            $this->warn('Продукты не найдены');
            return 0;
        }

        $this->info("Найдено продуктов: {$products->count()}");

        // 3. Обрабатываем каждый продукт
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $processed = 0;
        $errors = 0;

        foreach ($products as $product) {
            try {
                // Запускаем джобу для скачивания изображений и создания конверсий
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

        return 0;
    }

    /**
     * Удаляет ВСЮ медиа продуктов из БД и файлы
     */
    protected function deleteAllProductMedia(): void
    {
        // Получаем все медиа продуктов
        $productMedia = Media::where('model_type', Product::class)->get();
        
        $this->info("Найдено медиа файлов продуктов: {$productMedia->count()}");

        $deletedFiles = 0;
        $deletedRecords = 0;

        foreach ($productMedia as $media) {
            try {
                // Удаляем физический файл
                $filePath = $media->getPath();
                if (File::exists($filePath)) {
                    File::delete($filePath);
                    $deletedFiles++;
                }

                // Удаляем папку конверсий
                $conversionsPath = dirname($filePath) . '/conversions';
                if (File::exists($conversionsPath)) {
                    File::deleteDirectory($conversionsPath);
                }

                // Удаляем папку медиа (если пустая)
                $mediaDir = dirname($filePath);
                if (File::exists($mediaDir) && count(File::allFiles($mediaDir)) === 0) {
                    File::deleteDirectory($mediaDir);
                }

                // Удаляем запись из БД
                $media->delete();
                $deletedRecords++;

            } catch (\Exception $e) {
                $this->warn("Ошибка удаления медиа {$media->id}: " . $e->getMessage());
            }
        }

        $this->info("Удалено файлов: {$deletedFiles}");
        $this->info("Удалено записей из БД: {$deletedRecords}");
    }
}
