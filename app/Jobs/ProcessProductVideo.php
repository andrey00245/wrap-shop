<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessProductVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function handle(): void
    {
        $media = $this->media;
        
        // Проверяем, что это видео
        if (!str_starts_with($media->mime_type, 'video/')) {
            Log::info("Файл {$media->file_name} не является видео, пропускаем конвертацию");
            return;
        }

        // Проверяем, что ffmpeg доступен
        $ffmpegPath = config('media-library.ffmpeg_path', '/usr/bin/ffmpeg');
        if (!file_exists($ffmpegPath) && !shell_exec("which ffmpeg")) {
            Log::warning("FFmpeg не найден. Конвертация видео невозможна. Установите ffmpeg или укажите путь в .env (FFMPEG_PATH)");
            return;
        }

        // Получаем путь к оригинальному видео
        $originalPath = $media->getPath();
        
        if (!file_exists($originalPath)) {
            Log::error("Оригинальный файл видео не найден: {$originalPath}");
            return;
        }

        try {
            // Создаем директорию для конвертированных видео
            $conversionsPath = storage_path('app/public/media-library/conversions/' . $media->id);
            if (!is_dir($conversionsPath)) {
                mkdir($conversionsPath, 0755, true);
            }

            // Создаем постер (первый кадр видео) как изображение
            $this->createVideoPoster($media, $originalPath, $conversionsPath);

            // Конвертируем видео для мобильных (720p, битрейт 2Mbps)
            $this->convertVideo($media, $originalPath, $conversionsPath, 'mobile', 1280, 720, 2000);
            
            // Конвертируем видео для десктопа (1080p, битрейт 5Mbps)
            $this->convertVideo($media, $originalPath, $conversionsPath, 'desktop', 1920, 1080, 5000);

            Log::info("✅ Конвертация видео {$media->file_name} завершена успешно");
        } catch (\Exception $e) {
            Log::error("Ошибка при конвертации видео {$media->file_name}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Создает постер (первый кадр) из видео
     */
    protected function createVideoPoster(Media $media, string $inputPath, string $outputDir): void
    {
        $ffmpegPath = config('media-library.ffmpeg_path', '/usr/bin/ffmpeg');
        $ffmpegPath = trim(shell_exec("which ffmpeg") ?: '') ?: $ffmpegPath;
        
        $posterPath = $outputDir . '/poster_' . pathinfo($media->file_name, PATHINFO_FILENAME) . '.jpg';
        
        // Команда ffmpeg для извлечения первого кадра
        // -ss 1: извлекаем кадр на 1 секунде (чтобы избежать черного экрана)
        // -i: входной файл
        // -vframes 1: только один кадр
        // -q:v 2: качество JPEG (2 = высокое качество)
        $command = sprintf(
            '%s -y -ss 1 -i %s -vframes 1 -q:v 2 %s 2>&1',
            escapeshellarg($ffmpegPath),
            escapeshellarg($inputPath),
            escapeshellarg($posterPath)
        );

        Log::info("Создаем постер видео");
        Log::info("Команда: {$command}");

        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0 || !file_exists($posterPath)) {
            $errorOutput = implode("\n", $output);
            Log::warning("Ошибка создания постера видео: {$errorOutput}");
            return;
        }

        // Сохраняем постер как конверсию
        $this->saveConversion($media, $posterPath, 'poster');
        
        Log::info("✅ Постер создан: {$posterPath}");
    }

    /**
     * Конвертирует видео в указанный формат
     */
    protected function convertVideo(Media $media, string $inputPath, string $outputDir, string $conversionName, int $width, int $height, int $bitrate): void
    {
        $ffmpegPath = config('media-library.ffmpeg_path', '/usr/bin/ffmpeg');
        $ffmpegPath = trim(shell_exec("which ffmpeg") ?: '') ?: $ffmpegPath;
        
        $outputPath = $outputDir . '/' . $conversionName . '_' . $media->file_name;
        
        // Команда ffmpeg для конвертации
        // -y: перезаписать выходной файл если существует
        // -i: входной файл
        // -vf scale: масштабирование с сохранением пропорций
        // -b:v: битрейт видео
        // -c:v libx264: кодек H.264
        // -preset medium: баланс скорости и качества
        // -crf 23: качество (18-28, меньше = лучше качество)
        // -c:a aac: аудио кодек AAC
        // -b:a 128k: битрейт аудио
        // -movflags +faststart: для веб-стриминга
        $command = sprintf(
            '%s -y -i %s -vf "scale=%d:%d:force_original_aspect_ratio=decrease,pad=%d:%d:(ow-iw)/2:(oh-ih)/2" -c:v libx264 -preset medium -crf 23 -maxrate %dk -bufsize %dk -c:a aac -b:a 128k -movflags +faststart %s 2>&1',
            escapeshellarg($ffmpegPath),
            escapeshellarg($inputPath),
            $width,
            $height,
            $width,
            $height,
            $bitrate,
            $bitrate * 2, // bufsize = 2x bitrate для плавного воспроизведения
            escapeshellarg($outputPath)
        );

        Log::info("Выполняем конвертацию видео: {$conversionName}");
        Log::info("Команда: {$command}");

        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0 || !file_exists($outputPath)) {
            $errorOutput = implode("\n", $output);
            throw new \Exception("Ошибка конвертации видео {$conversionName}: {$errorOutput}");
        }

        // Сохраняем конвертированное видео в Media Library
        $this->saveConversion($media, $outputPath, $conversionName);
        
        Log::info("✅ Конвертация {$conversionName} завершена: {$outputPath}");
    }

    /**
     * Сохраняет конвертированное видео как конверсию в Media Library
     */
    protected function saveConversion(Media $media, string $convertedPath, string $conversionName): void
    {
        // Получаем правильный путь для конверсии согласно структуре Spatie Media Library
        // Формат: storage/app/public/media-library/{id}/{conversion_name}/{file_name}
        $originalPath = $media->getPath();
        $conversionDirectory = dirname($originalPath) . '/' . $conversionName;
        
        // Создаем директорию если нужно
        if (!is_dir($conversionDirectory)) {
            mkdir($conversionDirectory, 0755, true);
        }

        // Определяем имя файла для конверсии
        // Для постера используем оригинальное имя с расширением .jpg
        if ($conversionName === 'poster') {
            $fileName = pathinfo($media->file_name, PATHINFO_FILENAME) . '.jpg';
        } else {
            $fileName = $media->file_name;
        }

        // Копируем файл в правильное место
        $destinationPath = $conversionDirectory . '/' . $fileName;
        
        if (!copy($convertedPath, $destinationPath)) {
            throw new \Exception("Не удалось скопировать конвертированное видео в {$destinationPath}");
        }

        // Регистрируем конверсию в базе данных через метод модели
        // Spatie Media Library автоматически определит конверсию при вызове getUrl()
        // Но нужно убедиться, что файл находится в правильном месте
        Log::info("Конверсия {$conversionName} сохранена: {$destinationPath}");
        
        // Очищаем временный файл
        if (file_exists($convertedPath) && $convertedPath !== $destinationPath) {
            @unlink($convertedPath);
        }
    }
}
