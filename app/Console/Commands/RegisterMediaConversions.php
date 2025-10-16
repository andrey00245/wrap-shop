<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Product;

class RegisterMediaConversions extends Command
{
    protected $signature = 'media:register-conversions';
    protected $description = 'Регистрация конверсий для всех существующих медиа файлов';

    public function handle()
    {
        $this->info("🔄 Регистрация конверсий для существующих медиа файлов...");
        
        $mediaFiles = Media::where('collection_name', 'images')
            ->whereIn('mime_type', [
                'image/jpeg',
                'image/png', 
                'image/gif',
                'image/bmp'
            ])
            ->get();
        
        $this->info("Найдено изображений: {$mediaFiles->count()}");
        
        $processed = 0;
        $errors = 0;
        
        foreach ($mediaFiles as $media) {
            try {
                $model = $media->model;
                if (!$model) {
                    $this->warn("Медиа {$media->file_name} не привязано к модели, пропускаем");
                    continue;
                }
                
                // Принудительно регистрируем конверсии
                $model->registerMediaConversions($media);
                
                $processed++;
                
            } catch (\Exception $e) {
                $this->error("Ошибка обработки {$media->file_name}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->info("\n✅ Регистрация завершена!");
        $this->info("Обработано: {$processed}");
        $this->info("Ошибок: {$errors}");
        
        $this->info("\nТеперь запустите: php artisan media:regenerate --force");
        
        return 0;
    }
}

