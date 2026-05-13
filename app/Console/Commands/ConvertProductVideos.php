<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Jobs\ProcessProductVideo;

class ConvertProductVideos extends Command
{
    protected $signature = 'video:convert {--force : Принудительно переконвертировать все видео}';
    protected $description = 'Конвертация видео продуктов в оптимизированные версии (мобильная и десктоп)';

    public function handle(): int
    {
        $this->info('🎬 Начинаем конвертацию видео продуктов...');

        // Получаем все видео из коллекции videos
        $videos = Media::where('collection_name', 'videos')
            ->where('mime_type', 'like', 'video/%')
            ->get();

        if ($videos->isEmpty()) {
            $this->warn('Видео не найдены');
            return 0;
        }

        $this->info("Найдено видео: {$videos->count()}");

        $force = $this->option('force');
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar($videos->count());
        $bar->start();

        foreach ($videos as $video) {
            try {
                // Проверяем, существуют ли уже конверсии
                if (!$force && $video->hasGeneratedConversion('mobile') && $video->hasGeneratedConversion('desktop')) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Запускаем конвертацию
                ProcessProductVideo::dispatch($video);
                $processed++;
                $bar->advance();

            } catch (\Exception $e) {
                $this->error("\nОшибка при обработке видео {$video->file_name}: " . $e->getMessage());
                $errors++;
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Обработка завершена!");
        $this->info("Обработано: {$processed}");
        $this->info("Пропущено (уже конвертированы): {$skipped}");
        $this->info("Ошибок: {$errors}");

        if ($processed > 0) {
            $this->info("\n⚠️ Конвертация выполняется в фоне через очередь. Проверьте логи для отслеживания прогресса.");
        }

        return 0;
    }
}
