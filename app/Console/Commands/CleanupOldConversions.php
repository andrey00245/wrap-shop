<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Models\MediaConversions;

class CleanupOldConversions extends Command
{
    protected $signature = 'media:cleanup-old-conversions {--dry-run : Показать что будет удалено без удаления}';
    protected $description = 'Удаление старых неиспользуемых конверсий изображений';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $currentConversions = array_keys(MediaConversions::getConversionsConfig());
        
        $this->info("🧹 Очистка старых конверсий...");
        $this->info("Текущие конверсии: " . implode(', ', $currentConversions));
        
        if ($dryRun) {
            $this->warn("🔍 РЕЖИМ ПРЕДВАРИТЕЛЬНОГО ПРОСМОТРА - файлы НЕ будут удалены");
        }
        
        $mediaFiles = Media::where('collection_name', 'images')->get();
        $totalFiles = $mediaFiles->count();
        
        if ($totalFiles === 0) {
            $this->warn('Медиа файлы не найдены');
            return 0;
        }
        
        $this->info("Найдено медиа файлов: {$totalFiles}");
        
        $deletedFiles = 0;
        $savedSpace = 0;
        $oldConversions = ['preview', 'thumbnail', 'webp']; // Старые конверсии для удаления
        
        foreach ($mediaFiles as $media) {
            try {
                $mediaPath = $media->getPath();
                $conversionsPath = dirname($mediaPath) . '/conversions/';
                
                if (!is_dir($conversionsPath)) {
                    continue;
                }
                
                // Получаем все файлы конверсий
                $conversionFiles = glob($conversionsPath . '*');
                
                foreach ($conversionFiles as $file) {
                    $filename = basename($file);
                    
                    // Проверяем, является ли это старой конверсией
                    $isOldConversion = false;
                    foreach ($oldConversions as $oldConversion) {
                        if (strpos($filename, "-{$oldConversion}.") !== false) {
                            $isOldConversion = true;
                            break;
                        }
                    }
                    
                    if ($isOldConversion) {
                        $fileSize = filesize($file);
                        $savedSpace += $fileSize;
                        
                        if (!$dryRun) {
                            if (unlink($file)) {
                                $deletedFiles++;
                            }
                        } else {
                            $deletedFiles++;
                        }
                    }
                }
                
            } catch (\Exception $e) {
                $this->error("\nОшибка обработки {$media->file_name}: " . $e->getMessage());
            }
        }
        
        $this->info("\n\n📊 РЕЗУЛЬТАТЫ:");
        $this->info("Файлов для удаления: {$deletedFiles}");
        $this->info("Экономия места: " . $this->formatBytes($savedSpace));
        
        if ($dryRun) {
            $this->warn("\n🔍 Это был предварительный просмотр. Для реального удаления запустите без --dry-run");
        } else {
            $this->info("\n✅ Очистка завершена!");
        }
        
        return 0;
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
