<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Product;
use App\Models\Category;
use App\Models\News;
use App\Models\Banner;
use App\Models\CustomBlock;
use App\Models\ProductBanner;
use Illuminate\Support\Facades\Storage;

class CleanupUnusedMedia extends Command
{
    protected $signature = 'media:cleanup-unused {--collection=images : Коллекция для очистки} {--force : Принудительное удаление без подтверждения} {--batch-size=100 : Размер батча для обработки}';
    protected $description = 'Безопасная очистка неиспользуемых медиа файлов';

    public function handle()
    {
        $collection = $this->option('collection');
        $force = $this->option('force');
        $batchSize = (int) $this->option('batch-size');
        
        $this->info("🧹 ОЧИСТКА НЕИСПОЛЬЗУЕМЫХ МЕДИА - Коллекция: {$collection}");
        $this->info("=" . str_repeat("=", 60));
        
        // Получаем неиспользуемые медиа
        $unusedMedia = $this->getUnusedMedia($collection);
        
        if ($unusedMedia->isEmpty()) {
            $this->info("✅ Неиспользуемых медиа файлов не найдено!");
            return 0;
        }
        
        $totalSize = $unusedMedia->sum('size');
        $totalSizeFormatted = $this->formatBytes($totalSize);
        
        $this->warn("Найдено неиспользуемых медиа: {$unusedMedia->count()}");
        $this->warn("Общий размер: {$totalSizeFormatted}");
        
        // Подтверждение удаления
        if (!$force) {
            if (!$this->confirm("Вы уверены, что хотите удалить эти файлы? Это действие необратимо!")) {
                $this->info("Операция отменена.");
                return 0;
            }
            
            if (!$this->confirm("Вы создали бэкап базы данных?")) {
                $this->error("Создайте бэкап перед продолжением!");
                return 1;
            }
        }
        
        // Очистка по батчам
        $this->cleanupInBatches($unusedMedia, $batchSize);
        
        return 0;
    }
    
    private function getUnusedMedia($collection)
    {
        // Получаем все медиа файлы в коллекции
        $allMedia = Media::where('collection_name', $collection)->get();
        
        // Получаем используемые медиа
        $usedMediaIds = $this->getUsedMediaIds($collection);
        
        // Возвращаем неиспользуемые
        return $allMedia->whereNotIn('id', $usedMediaIds);
    }
    
    private function getUsedMediaIds($collection)
    {
        $usedIds = collect();
        
        // Медиа привязанные к различным моделям
        $modelTypes = [
            Product::class,
            Category::class,
            News::class,
            Banner::class,
            CustomBlock::class,
            ProductBanner::class,
        ];
        
        foreach ($modelTypes as $modelType) {
            $media = Media::where('collection_name', $collection)
                ->where('model_type', $modelType)
                ->whereHas('model')
                ->pluck('id');
            $usedIds = $usedIds->merge($media);
        }
        
        return $usedIds->unique()->values();
    }
    
    private function cleanupInBatches($unusedMedia, $batchSize)
    {
        $totalCount = $unusedMedia->count();
        $totalSize = $unusedMedia->sum('size');
        $deletedCount = 0;
        $deletedSize = 0;
        $errors = 0;
        
        $this->info("\n🚀 Начинаем очистку...");
        
        $bar = $this->output->createProgressBar($totalCount);
        $bar->start();
        
        // Обрабатываем по батчам
        $chunks = $unusedMedia->chunk($batchSize);
        
        foreach ($chunks as $chunk) {
            foreach ($chunk as $media) {
                try {
                    // Удаляем файл с диска
                    if (Storage::disk($media->disk)->exists($media->getPath())) {
                        Storage::disk($media->disk)->delete($media->getPath());
                    }
                    
                    // Удаляем все конверсии
                    $this->deleteConversions($media);
                    
                    // Удаляем запись из БД
                    $media->delete();
                    
                    $deletedCount++;
                    $deletedSize += $media->size;
                    
                } catch (\Exception $e) {
                    $this->error("\nОшибка удаления {$media->file_name}: " . $e->getMessage());
                    $errors++;
                }
                
                $bar->advance();
            }
            
            // Небольшая пауза между батчами
            usleep(100000); // 0.1 секунды
        }
        
        $bar->finish();
        
        // Результаты
        $this->info("\n\n✅ ОЧИСТКА ЗАВЕРШЕНА!");
        $this->info("Удалено файлов: {$deletedCount}/{$totalCount}");
        $this->info("Освобождено места: " . $this->formatBytes($deletedSize));
        $this->info("Ошибок: {$errors}");
        
        if ($errors > 0) {
            $this->warn("⚠️ Некоторые файлы не удалось удалить. Проверьте логи.");
        }
        
        // Показываем экономию
        $savingsPercent = round(($deletedSize / $totalSize) * 100, 1);
        $this->info("Экономия: {$savingsPercent}% от общего размера неиспользуемых файлов");
    }
    
    private function deleteConversions($media)
    {
        try {
            $conversions = [
                'preview',
                'thumbnail',
                'gallery',
                'webp',
                'preview_webp'
            ];
            
            foreach ($conversions as $conversion) {
                $conversionPath = $media->getPath($conversion);
                if (Storage::disk($media->disk)->exists($conversionPath)) {
                    Storage::disk($media->disk)->delete($conversionPath);
                }
            }
        } catch (\Exception $e) {
            // Игнорируем ошибки при удалении конверсий
        }
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
