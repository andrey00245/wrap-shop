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

class AnalyzeUnusedMedia extends Command
{
    protected $signature = 'media:analyze-unused {--collection=images : Коллекция для анализа} {--dry-run : Показать что будет удалено без удаления}';
    protected $description = 'Анализ неиспользуемых медиа файлов';

    public function handle()
    {
        $collection = $this->option('collection');
        $isDryRun = $this->option('dry-run');
        
        $this->info("🔍 АНАЛИЗ НЕИСПОЛЬЗУЕМЫХ МЕДИА - Коллекция: {$collection}");
        
        // Получаем все медиа файлы в коллекции
        $allMedia = Media::where('collection_name', $collection)->get();
        $this->info("Всего медиа файлов в коллекции '{$collection}': {$allMedia->count()}");
        
        // Получаем используемые медиа
        $usedMediaIds = $this->getUsedMediaIds($collection);
        $this->info("Используемых медиа файлов: " . count($usedMediaIds));
        
        // Находим неиспользуемые
        $unusedMedia = $allMedia->whereNotIn('id', $usedMediaIds);
        $this->info("Неиспользуемых медиа файлов: " . $unusedMedia->count());
        
        if ($unusedMedia->isEmpty()) {
            $this->info("✅ Все медиа файлы используются!");
            return 0;
        }
        
        // Анализируем неиспользуемые медиа
        $this->analyzeUnusedMedia($unusedMedia);
        
        // Показываем детали
        $this->showUnusedDetails($unusedMedia, $isDryRun);
        
        // Показываем потенциальную экономию
        $this->showPotentialSavings($unusedMedia);
        
        return 0;
    }
    
    private function getUsedMediaIds($collection)
    {
        $usedIds = collect();
        
        // 1. Медиа привязанные к товарам
        $productMedia = Media::where('collection_name', $collection)
            ->where('model_type', Product::class)
            ->whereHas('model')
            ->pluck('id');
        $usedIds = $usedIds->merge($productMedia);
        
        // 2. Медиа привязанные к категориям
        $categoryMedia = Media::where('collection_name', $collection)
            ->where('model_type', Category::class)
            ->whereHas('model')
            ->pluck('id');
        $usedIds = $usedIds->merge($categoryMedia);
        
        // 3. Медиа привязанные к новостям
        $newsMedia = Media::where('collection_name', $collection)
            ->where('model_type', News::class)
            ->whereHas('model')
            ->pluck('id');
        $usedIds = $usedIds->merge($newsMedia);
        
        // 4. Медиа привязанные к баннерам
        $bannerMedia = Media::where('collection_name', $collection)
            ->where('model_type', Banner::class)
            ->whereHas('model')
            ->pluck('id');
        $usedIds = $usedIds->merge($bannerMedia);
        
        // 5. Медиа привязанные к кастомным блокам
        $customBlockMedia = Media::where('collection_name', $collection)
            ->where('model_type', CustomBlock::class)
            ->whereHas('model')
            ->pluck('id');
        $usedIds = $usedIds->merge($customBlockMedia);
        
        // 6. Медиа привязанные к баннерам товаров
        $productBannerMedia = Media::where('collection_name', $collection)
            ->where('model_type', ProductBanner::class)
            ->whereHas('model')
            ->pluck('id');
        $usedIds = $usedIds->merge($productBannerMedia);
        
        return $usedIds->unique()->values();
    }
    
    private function analyzeUnusedMedia($unusedMedia)
    {
        $this->info("\n📊 АНАЛИЗ НЕИСПОЛЬЗУЕМЫХ МЕДИА:");
        
        // Группировка по типам моделей
        $byModelType = $unusedMedia->groupBy('model_type');
        
        foreach ($byModelType as $modelType => $media) {
            $count = $media->count();
            $size = $media->sum('size');
            $sizeFormatted = $this->formatBytes($size);
            
            $this->line("  {$modelType}: {$count} файлов ({$sizeFormatted})");
        }
        
        // Группировка по размерам файлов
        $this->info("\n📏 РАСПРЕДЕЛЕНИЕ ПО РАЗМЕРАМ:");
        $sizeRanges = [
            'Малые (< 100KB)' => $unusedMedia->where('size', '<', 100 * 1024)->count(),
            'Средние (100KB - 1MB)' => $unusedMedia->whereBetween('size', [100 * 1024, 1024 * 1024])->count(),
            'Большие (1MB - 5MB)' => $unusedMedia->whereBetween('size', [1024 * 1024, 5 * 1024 * 1024])->count(),
            'Очень большие (> 5MB)' => $unusedMedia->where('size', '>', 5 * 1024 * 1024)->count(),
        ];
        
        foreach ($sizeRanges as $range => $count) {
            if ($count > 0) {
                $percentage = round(($count / $unusedMedia->count()) * 100, 1);
                $this->line("  {$range}: {$count} ({$percentage}%)");
            }
        }
    }
    
    private function showUnusedDetails($unusedMedia, $isDryRun)
    {
        $this->info("\n🗑️ ДЕТАЛИ НЕИСПОЛЬЗУЕМЫХ МЕДИА:");
        
        $totalSize = $unusedMedia->sum('size');
        $this->info("Общий размер неиспользуемых файлов: " . $this->formatBytes($totalSize));
        
        // Показываем примеры файлов
        $this->info("\nПримеры неиспользуемых файлов:");
        $examples = $unusedMedia->take(10);
        
        foreach ($examples as $media) {
            $size = $this->formatBytes($media->size);
            $this->line("  - {$media->file_name} ({$size}) - {$media->model_type}");
        }
        
        if ($unusedMedia->count() > 10) {
            $this->line("  ... и еще " . ($unusedMedia->count() - 10) . " файлов");
        }
        
        if ($isDryRun) {
            $this->warn("\n⚠️ DRY RUN - файлы НЕ будут удалены");
            $this->info("Для удаления запустите: php artisan media:cleanup-unused --collection={$this->option('collection')}");
        }
    }
    
    private function showPotentialSavings($unusedMedia)
    {
        $this->info("\n💰 ПОТЕНЦИАЛЬНАЯ ЭКОНОМИЯ:");
        
        $totalSize = $unusedMedia->sum('size');
        $totalSizeMB = round($totalSize / 1024 / 1024, 2);
        $totalSizeGB = round($totalSize / 1024 / 1024 / 1024, 2);
        
        $this->info("Можно освободить: {$totalSizeMB} MB ({$totalSizeGB} GB)");
        $this->info("Количество файлов: {$unusedMedia->count()}");
        
        if ($totalSizeGB > 1) {
            $this->warn("⚠️ Это значительная экономия места!");
        }
        
        $this->info("\n🎯 РЕКОМЕНДАЦИИ:");
        $this->line("1. Сначала запустите анализ: php artisan media:analyze-unused --dry-run");
        $this->line("2. Проверьте результаты внимательно");
        $this->line("3. Создайте бэкап базы данных");
        $this->line("4. Запустите очистку: php artisan media:cleanup-unused");
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
