<?php

namespace App\Observers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use App\Jobs\ProcessProductVideo; // Больше не используется - видео через YouTube

class MediaObserver
{
    /**
     * Обработка после создания медиа файла
     */
    public function created(Media $media): void
    {
        // Видео теперь загружаются через YouTube ссылки (ProductVideo модель)
        // Обработка видео файлов больше не требуется
        // if ($media->collection_name === 'videos' && str_starts_with($media->mime_type, 'video/')) {
        //     (new ProcessProductVideo($media))->handle();
        // }
    }
}
