<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Product;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Image;
use Illuminate\Support\Facades\File;

class GenerateConversionsSync extends Command
{
    protected $signature = 'media:generate-sync {--collection=images : Коллекция для обработки} {--force : Принудительно пересоздать конверсии} {--only-missing : Создавать только отсутствующие конверсии}';
    protected $description = 'Синхронная генерация конверсий (без очереди)';

    public function handle()
    {
        $collection = $this->option('collection');
        $force = $this->option('force');
        $onlyMissing = $this->option('only-missing');

        $this->info("🖼️ Синхронная генерация конверсий для коллекции: {$collection}");

        // Исправляем права доступа
        $this->fixStoragePermissions();

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

        $bar = $this->output->createProgressBar($mediaFiles->count());
        $bar->start();

        $processed = 0;
        $errors = 0;

        foreach ($mediaFiles as $media) {
            try {
                // Получаем модель
                $model = $media->model;

                if (!$model) {
                    $this->warn("\nМедиа {$media->file_name} не привязано к модели, пропускаем");
                    continue;
                }

                // Генерируем конверсии синхронно
                $this->generateConversionsSync($media, $force, $onlyMissing);

                $processed++;

            } catch (\Exception $e) {
                $this->error("\nОшибка обработки {$media->file_name}: " . $e->getMessage());
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info("\n\n✅ Обработка завершена!");
        $this->info("Обработано: {$processed}");
        $this->info("Ошибок: {$errors}");

        // Показываем примеры URL
        $this->showExampleUrls($mediaFiles->first());

        return 0;
    }

    private function generateConversionsSync($media, $force = false, $onlyMissing = false)
    {
        // Используем конфигурацию из MediaConversions
        $conversions = \App\Models\MediaConversions::getConversionsConfig();
        
        // Получаем модель для создания конверсий
        $model = $media->model;
        if (!$model) {
            throw new \Exception("Медиа не привязано к модели");
        }

        foreach ($conversions as $conversionName => $config) {
            try {
                // Проверяем, существует ли оригинальный файл
                $originalPath = $media->getPath();
                if (!file_exists($originalPath)) {
                    $this->warn("\n⚠️ Оригинальный файл не найден: {$originalPath}");
                    continue;
                }

                // Проверяем, существует ли конверсия
                if (!$force && $media->hasGeneratedConversion($conversionName)) {
                    if ($onlyMissing) {
                        $this->line("\n⏭️ Конверсия {$conversionName} уже существует для {$media->file_name}");
                        continue;
                    } else {
                        $this->line("\n⏭️ Конверсия {$conversionName} уже существует для {$media->file_name}, пропускаем");
                        continue;
                    }
                }

                // Создаем конверсию через модель
                $conversion = $model->addMediaConversion($conversionName);

                if ($config['width'] && $config['height']) {
                    $conversion->width($config['width'])->height($config['height']);
                }

                if ($config['quality']) {
                    $conversion->quality($config['quality']);
                }

                if (isset($config['sharpen'])) {
                    $conversion->sharpen($config['sharpen']);
                }

                if ($config['format']) {
                    $conversion->format($config['format']);
                }

                // Используем contain для правильного масштабирования
                if (isset($config['fit'])) {
                    $conversion->fit(\Spatie\Image\Enums\Fit::Contain);
                }

                $conversion->optimize();

                foreach ($config['collections'] as $collection) {
                    $conversion->performOnCollections($collection);
                }

                $conversion->nonQueued(); // Отключаем очередь!

                // Создаем папку conversions, если она не существует
                $conversionsPath = dirname($originalPath) . '/conversions';
                if (!is_dir($conversionsPath)) {
                    mkdir($conversionsPath, 0755, true);
                    $this->line("\n📁 Создана папка conversions: {$conversionsPath}");
                }

                // Выполняем конверсию напрямую
                $conversion->perform();

                $this->line("\n✅ Создана конверсия {$conversionName} для {$media->file_name}");

            } catch (\Exception $e) {
                $this->warn("\n❌ Не удалось создать конверсию {$conversionName} для {$media->file_name}: " . $e->getMessage());
            }
        }
    }

    private function showExampleUrls($media)
    {
        if (!$media) return;

        $this->info("\n📋 Примеры URL для {$media->file_name}:");
        $this->line("Оригинал: " . $media->getUrl());
        
        // Показываем только существующие конверсии
        if ($media->hasGeneratedConversion('preview')) {
            $this->line("Preview: " . $media->getUrl('preview'));
        }
        if ($media->hasGeneratedConversion('preview_webp')) {
            $this->line("Preview WebP: " . $media->getUrl('preview_webp'));
        }
        if ($media->hasGeneratedConversion('gallery')) {
            $this->line("Gallery: " . $media->getUrl('gallery'));
        }
    }

    private function fixStoragePermissions()
    {
        $this->info("🔧 Исправление прав доступа к файловой системе...");
        
        $paths = [
            storage_path(),
            base_path('bootstrap/cache'),
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                File::chmod($path, 0775);
                $this->recurseChmod($path);
                $this->line("✅ Исправлены права для: {$path}");
            }
        }
    }

    private function recurseChmod($path)
    {
        foreach (File::allFiles($path) as $file) {
            @chmod($file->getRealPath(), 0664);
        }
        foreach (File::directories($path) as $dir) {
            @chmod($dir, 0775);
            $this->recurseChmod($dir);
        }
    }
}
