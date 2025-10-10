<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\MediaConversions;

class GenerateMediaConversions extends Command
{
    protected $signature = 'media:generate-conversions {--collection=images : Коллекция для обработки} {--force : Принудительно пересоздать конверсии}';
    protected $description = 'Генерация всех конверсий для медиа файлов';

    public function handle()
    {
        $collection = $this->option('collection');
        $force = $this->option('force');
        
        $this->info("🖼️ Генерация конверсий для коллекции: {$collection}");
        
        // Получаем медиа файлы
        $mediaFiles = Media::where('collection_name', $collection)
            ->whereIn('mime_type', [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp'
            ])
            ->get();
        
        $this->info("Найдено изображений: {$mediaFiles->count()}");
        
        if ($mediaFiles->isEmpty()) {
            $this->warn('Нет изображений для обработки');
            return 0;
        }
        
        $processed = 0;
        $errors = 0;
        
        foreach ($mediaFiles as $media) {
            try {
                // Получаем модель для регистрации конверсий
                $model = $media->model;
                
                if (!$model) {
                    $this->warn("\nМедиа {$media->file_name} не привязано к модели, пропускаем");
                    continue;
                }
                
                // Генерируем конверсии из конфигурации
                $conversions = array_keys(MediaConversions::getConversionsConfig());
                
                foreach ($conversions as $conversionName) {
                    if ($force || !$media->hasGeneratedConversion($conversionName)) {
                        try {
                            // Создаем конверсию напрямую
                            $this->createConversionForMedia($media, $conversionName);
                        } catch (\Exception $e) {
                            $this->error("\nОшибка создания конверсии {$conversionName} для {$media->file_name}: " . $e->getMessage());
                            $errors++;
                        }
                    }
                }
                
                $processed++;
                
            } catch (\Exception $e) {
                $this->error("\nОшибка обработки {$media->file_name}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->info("\n\n✅ Обработка завершена!");
        $this->info("Обработано: {$processed}");
        $this->info("Ошибок: {$errors}");
        
        // Показываем статистику размеров
        $this->showSizeStatistics($collection);
        
        return 0;
    }
    
    private function getConversionWidth($conversionName)
    {
        $config = MediaConversions::getConversionsConfig();
        return $config[$conversionName]['width'] ?? null;
    }
    
    private function getConversionHeight($conversionName)
    {
        $config = MediaConversions::getConversionsConfig();
        return $config[$conversionName]['height'] ?? null;
    }
    
    private function getConversionQuality($conversionName)
    {
        $config = MediaConversions::getConversionsConfig();
        return $config[$conversionName]['quality'] ?? 85;
    }
    
    private function getConversionFormat($conversionName)
    {
        $config = MediaConversions::getConversionsConfig();
        return $config[$conversionName]['format'] ?? null;
    }
    
    private function getConversionSharpen($conversionName)
    {
        $config = MediaConversions::getConversionsConfig();
        return $config[$conversionName]['sharpen'] ?? 0;
    }
    
    private function createConversionForMedia($media, $conversionName)
    {
        $config = MediaConversions::getConversionsConfig()[$conversionName] ?? null;
        
        if (!$config) {
            return;
        }
        
        // Получаем модель для создания конверсии
        $model = $media->model;
        if (!$model) {
            throw new \Exception("Медиа не привязано к модели");
        }
        
        // Создаем конверсию через модель
        $conversion = $model->addMediaConversion($conversionName);
        
        if ($config['width'] && $config['height']) {
            $conversion->width($config['width'])->height($config['height']);
        }
        
        if ($config['quality']) {
            $conversion->quality($config['quality']);
        }
        
        if ($config['sharpen']) {
            $conversion->sharpen($config['sharpen']);
        }
        
        if ($config['format']) {
            $conversion->format($config['format']);
        }
        
        $conversion->optimize();
        
        foreach ($config['collections'] as $collection) {
            $conversion->performOnCollections($collection);
        }
        
        // Выполняем конверсию для конкретного медиа файла
        $conversion->performOnMedia($media);
    }
    
    private function showSizeStatistics($collection)
    {
        $this->info("\n📊 Статистика размеров:");
        
        $totalOriginal = Media::where('collection_name', $collection)
            ->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'])
            ->sum('size');
            
        $this->info("Оригинальные файлы: " . round($totalOriginal / 1024 / 1024, 2) . " MB");
        
        // Подсчитываем размеры конверсий (примерно)
        $estimatedPreviewWebp = $totalOriginal * 0.08; // preview_webp обычно в 12 раз меньше
        $estimatedGallery = $totalOriginal * 0.15; // gallery немного меньше оригинала
        
        $this->info("Ожидаемый размер preview_webp: " . round($estimatedPreviewWebp / 1024 / 1024, 2) . " MB");
        $this->info("Ожидаемый размер gallery: " . round($estimatedGallery / 1024 / 1024, 2) . " MB");
        $this->info("Общая экономия: " . round(($totalOriginal - $estimatedPreviewWebp) / 1024 / 1024, 2) . " MB");
    }
}
