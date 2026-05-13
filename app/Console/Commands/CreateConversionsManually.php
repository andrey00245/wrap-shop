<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CreateConversionsManually extends Command
{
    protected $signature = 'media:create-conversions-manually {--limit=10 : Количество файлов для обработки}';
    protected $description = 'Создание конверсий вручную через PHP GD';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("🖼️ Создание конверсий вручную (лимит: {$limit})");
        
        $mediaFiles = Media::where('collection_name', 'images')
            ->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'])
            ->take($limit)
            ->get();
            
        $this->info("Найдено изображений: {$mediaFiles->count()}");
        
        $processed = 0;
        $errors = 0;
        
        foreach ($mediaFiles as $media) {
            try {
                $originalPath = $media->getPath();
                $conversionPath = dirname($originalPath) . '/conversions/';
                
                if (!is_dir($conversionPath)) {
                    mkdir($conversionPath, 0755, true);
                }
                
                $conversionFile = $conversionPath . basename($originalPath, '.' . $media->extension) . '-preview_webp.webp';
                
                // Создаем конверсию через PHP GD
                $image = $this->createImageFromFile($originalPath);
                if (!$image) {
                    $this->error("Не удалось загрузить изображение: {$media->file_name}");
                    $errors++;
                    continue;
                }
                
                $resized = imagecreatetruecolor(482, 482);
                
                // Устанавливаем белый фон
                $white = imagecolorallocate($resized, 255, 255, 255);
                imagefill($resized, 0, 0, $white);
                
                // Сохраняем прозрачность для PNG
                if ($media->mime_type === 'image/png') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                }
                
                imagecopyresampled($resized, $image, 0, 0, 0, 0, 482, 482, imagesx($image), imagesy($image));
                
                if (imagewebp($resized, $conversionFile, 100)) {
                    $this->info("✅ Создан: " . basename($conversionFile));
                    $processed++;
                } else {
                    $this->error("❌ Ошибка создания: " . basename($conversionFile));
                    $errors++;
                }
                
                imagedestroy($image);
                imagedestroy($resized);
                
            } catch (\Exception $e) {
                $this->error("Ошибка обработки {$media->file_name}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->info("\n✅ Обработка завершена!");
        $this->info("Обработано: {$processed}");
        $this->info("Ошибок: {$errors}");
        
        return 0;
    }
    
    private function createImageFromFile($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($path);
            case 'png':
                return imagecreatefrompng($path);
            case 'gif':
                return imagecreatefromgif($path);
            case 'bmp':
                return imagecreatefrombmp($path);
            default:
                return false;
        }
    }
}
