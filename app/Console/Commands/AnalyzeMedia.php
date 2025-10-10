<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Product;
use App\Helpers\MediaHelper;

class AnalyzeMedia extends Command
{
    protected $signature = 'media:analyze {--collection=images : Коллекция для анализа}';
    protected $description = 'Анализ текущего состояния медиа файлов';

    public function handle()
    {
        $collection = $this->option('collection');
        
        $this->info("📊 АНАЛИЗ МЕДИА ФАЙЛОВ - Коллекция: {$collection}");
        $this->info("=" . str_repeat("=", 50));
        
        // Общая статистика
        $this->analyzeGeneralStats($collection);
        
        // Анализ конверсий
        $this->analyzeConversions($collection);
        
        // Анализ размеров
        $this->analyzeSizes($collection);
        
        // Рекомендации
        $this->showRecommendations();
        
        return 0;
    }
    
    private function analyzeGeneralStats($collection)
    {
        $this->info("\n📈 ОБЩАЯ СТАТИСТИКА:");
        
        $totalMedia = Media::where('collection_name', $collection)->count();
        $totalProducts = Product::count();
        $productsWithMedia = Product::whereHas('media', function($query) use ($collection) {
            $query->where('collection_name', $collection);
        })->count();
        
        $this->line("Всего медиа файлов: {$totalMedia}");
        $this->line("Всего товаров: {$totalProducts}");
        $this->line("Товары с медиа: {$productsWithMedia}");
        $this->line("Товары без медиа: " . ($totalProducts - $productsWithMedia));
        
        if ($totalMedia > 0) {
            $avgMediaPerProduct = round($totalMedia / $productsWithMedia, 2);
            $this->line("Среднее медиа на товар: {$avgMediaPerProduct}");
        }
    }
    
    private function analyzeConversions($collection)
    {
        $this->info("\n🔄 АНАЛИЗ КОНВЕРСИЙ:");
        
        $mediaFiles = Media::where('collection_name', $collection)
            ->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'])
            ->get();
        
        if ($mediaFiles->isEmpty()) {
            $this->warn("Нет изображений для анализа");
            return;
        }
        
        $conversions = [
            'preview' => 0,
            'thumbnail' => 0,
            'gallery' => 0,
            'webp' => 0,
            'preview_webp' => 0,
        ];
        
        foreach ($mediaFiles as $media) {
            $conversionsInfo = MediaHelper::getConversionsInfo($media);
            foreach ($conversions as $conversion => $count) {
                if ($conversionsInfo[$conversion]) {
                    $conversions[$conversion]++;
                }
            }
        }
        
        $total = $mediaFiles->count();
        
        foreach ($conversions as $conversion => $count) {
            $percentage = round(($count / $total) * 100, 1);
            $status = $count > 0 ? "✅" : "❌";
            $this->line("{$status} {$conversion}: {$count}/{$total} ({$percentage}%)");
        }
    }
    
    private function analyzeSizes($collection)
    {
        $this->info("\n📏 АНАЛИЗ РАЗМЕРОВ:");
        
        $mediaFiles = Media::where('collection_name', $collection)
            ->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'])
            ->get();
        
        if ($mediaFiles->isEmpty()) {
            return;
        }
        
        $totalSize = $mediaFiles->sum('size');
        $avgSize = $totalSize / $mediaFiles->count();
        $maxSize = $mediaFiles->max('size');
        $minSize = $mediaFiles->min('size');
        
        $this->line("Общий размер: " . $this->formatBytes($totalSize));
        $this->line("Средний размер: " . $this->formatBytes($avgSize));
        $this->line("Максимальный размер: " . $this->formatBytes($maxSize));
        $this->line("Минимальный размер: " . $this->formatBytes($minSize));
        
        // Анализ по размерам
        $sizeRanges = [
            'Малые (< 100KB)' => $mediaFiles->where('size', '<', 100 * 1024)->count(),
            'Средние (100KB - 1MB)' => $mediaFiles->whereBetween('size', [100 * 1024, 1024 * 1024])->count(),
            'Большие (1MB - 5MB)' => $mediaFiles->whereBetween('size', [1024 * 1024, 5 * 1024 * 1024])->count(),
            'Очень большие (> 5MB)' => $mediaFiles->where('size', '>', 5 * 1024 * 1024)->count(),
        ];
        
        $this->info("\nРаспределение по размерам:");
        foreach ($sizeRanges as $range => $count) {
            $percentage = round(($count / $mediaFiles->count()) * 100, 1);
            $this->line("  {$range}: {$count} ({$percentage}%)");
        }
    }
    
    private function showRecommendations()
    {
        $this->info("\n💡 РЕКОМЕНДАЦИИ:");
        
        $this->line("1. 🚀 Запустите: php artisan media:generate-conversions");
        $this->line("2. 📱 Используйте preview для каталога товаров");
        $this->line("3. 🖼️ Используйте gallery для детальной страницы");
        $this->line("4. ⚡ WebP версии для современной загрузки");
        $this->line("5. 🔍 Thumbnail для миниатюр");
        
        $this->info("\n🎯 ОЖИДАЕМЫЕ РЕЗУЛЬТАТЫ:");
        $this->line("• Уменьшение размера страниц на 60-80%");
        $this->line("• Ускорение загрузки каталога товаров");
        $this->line("• Улучшение Core Web Vitals");
        $this->line("• Экономия трафика для пользователей");
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
