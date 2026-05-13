<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\File;

class CleanImageMetadata extends Command
{
    protected $signature = 'media:clean-metadata {--limit=10 : Количество изображений для обработки}';
    protected $description = 'Очищает метаданные изображений от неправильных sRGB профилей';

    public function handle()
    {
        $limit = $this->option('limit');

        $this->info("🧹 Очистка метаданных изображений (лимит: {$limit})");

        $mediaFiles = Media::whereIn('mime_type', [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp'
        ])->take($limit)->get();

        if ($mediaFiles->isEmpty()) {
            $this->warn('Изображения не найдены');
            return 0;
        }

        $this->info("Найдено изображений: {$mediaFiles->count()}");

        $bar = $this->output->createProgressBar($mediaFiles->count());
        $bar->start();

        $processed = 0;
        $errors = 0;

        foreach ($mediaFiles as $media) {
            try {
                $this->cleanImageMetadata($media);
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

        return 0;
    }

    protected function cleanImageMetadata($media): void
    {
        $filePath = $media->getPath();

        if (!File::exists($filePath)) {
            return;
        }

        // Используем ImageMagick для очистки метаданных
        $command = "convert '{$filePath}' -strip '{$filePath}'";

        $output = [];
        $returnCode = 0;

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            $this->line("\n✅ Очищены метаданные для {$media->file_name}");
        } else {
            $this->warn("\n⚠️ Не удалось очистить метаданные для {$media->file_name}");
        }
    }
}
