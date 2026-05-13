<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CleanFileNames extends Command
{
    protected $signature = 'media:clean-filenames {--dry-run : Показать что будет переименовано без изменений}';
    protected $description = 'Очистка имен файлов от невидимых символов и проблемных символов';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info("🔍 Режим предварительного просмотра (dry-run)");
        } else {
            $this->info("🧹 Очистка имен файлов...");
        }

        $mediaFiles = Media::all();
        $this->info("Найдено медиа файлов: {$mediaFiles->count()}");

        $renamed = 0;
        $errors = 0;

        foreach ($mediaFiles as $media) {
            try {
                $originalName = $media->file_name;
                $cleanedName = $this->cleanFileName($originalName);
                
                if ($originalName !== $cleanedName) {
                    $this->line("📝 {$originalName} -> {$cleanedName}");
                    
                    if (!$dryRun) {
                        $this->renameFile($media, $cleanedName);
                    }
                    
                    $renamed++;
                }
            } catch (\Exception $e) {
                $this->error("❌ Ошибка обработки {$media->file_name}: " . $e->getMessage());
                $errors++;
            }
        }

        if ($dryRun) {
            $this->info("\n📊 Будет переименовано файлов: {$renamed}");
        } else {
            $this->info("\n✅ Обработка завершена!");
            $this->info("Переименовано: {$renamed}");
            $this->info("Ошибок: {$errors}");
        }

        return 0;
    }

    private function cleanFileName($fileName)
    {
        // Удаляем невидимые символы и комбинирующие диакритические знаки
        $cleaned = preg_replace('/[\x{0300}-\x{036F}]/u', '', $fileName);
        
        // Нормализуем Unicode (NFD -> NFC)
        $cleaned = \Normalizer::normalize($cleaned, \Normalizer::FORM_C);
        
        // Удаляем другие проблемные символы (только ASCII и кириллица)
        $cleaned = preg_replace('/[^\x20-\x7E\x{0400}-\x{04FF}]/u', '', $cleaned);
        
        // Заменяем пробелы на дефисы
        $cleaned = str_replace(' ', '-', $cleaned);
        
        // Удаляем множественные дефисы
        $cleaned = preg_replace('/-+/', '-', $cleaned);
        
        // Удаляем дефисы в начале и конце
        $cleaned = trim($cleaned, '-');
        
        // Если имя стало пустым, генерируем случайное
        if (empty($cleaned)) {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $cleaned = 'file-' . Str::random(10) . '.' . $extension;
        }
        
        return $cleaned;
    }

    private function renameFile($media, $newFileName)
    {
        $oldPath = $media->getPath();
        $newPath = dirname($oldPath) . '/' . $newFileName;
        
        if (File::exists($oldPath)) {
            File::move($oldPath, $newPath);
            
            // Обновляем запись в базе данных
            $media->file_name = $newFileName;
            $media->save();
            
            // Переименовываем конверсии если есть
            $this->renameConversions($media, $newFileName);
        }
    }

    private function renameConversions($media, $newFileName)
    {
        $conversionsPath = dirname($media->getPath()) . '/conversions';
        
        if (File::exists($conversionsPath)) {
            $files = File::files($conversionsPath);
            $baseName = pathinfo($media->file_name, PATHINFO_FILENAME);
            $newBaseName = pathinfo($newFileName, PATHINFO_FILENAME);
            
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                if (strpos($fileName, $baseName) === 0) {
                    $newConversionName = str_replace($baseName, $newBaseName, $fileName);
                    $newConversionPath = $conversionsPath . '/' . $newConversionName;
                    File::move($file->getPathname(), $newConversionPath);
                }
            }
        }
    }
}