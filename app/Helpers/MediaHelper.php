<?php

namespace App\Helpers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaHelper
{
    /**
     * Получить URL изображения для каталога товаров
     */
    public static function getCatalogImageUrl(Media $media): string
    {
        // Используем WebP версию preview
        if ($media->hasGeneratedConversion('preview_webp')) {
            return $media->getUrl('preview_webp');
        }

        // Fallback на оригинал
        return $media->getUrl();
    }

    /**
     * Получить URL изображения для галереи товара
     */
    public static function getGalleryImageUrl(Media $media): string
    {
        // Для галереи используем оптимизированную версию
        if ($media->hasGeneratedConversion('gallery')) {
            return $media->getUrl('gallery');
        }

        // Fallback на оригинал
        return $media->getUrl();
    }

    /**
     * Получить URL для модального окна (оригинал)
     */
    public static function getModalImageUrl(Media $media): string
    {
        return $media->getUrl();
    }

    /**
     * Получить URL миниатюры
     */
    public static function getThumbnailUrl(Media $media): string
    {
        if ($media->hasGeneratedConversion('thumbnail')) {
            return $media->getUrl('thumbnail');
        }

        return $media->getUrl('preview_webp');
    }

    /**
     * Проверить, есть ли WebP версия
     */
    public static function hasWebP(Media $media): bool
    {
        return $media->hasGeneratedConversion('webp') ||
               $media->hasGeneratedConversion('preview_webp');
    }

    /**
     * Получить WebP URL для каталога
     */
    public static function getWebPCatalogUrl(Media $media): ?string
    {
        if ($media->hasGeneratedConversion('preview_webp')) {
            return $media->getUrl('preview_webp');
        }

        return null;
    }

    /**
     * Получить WebP URL для галереи
     */
    public static function getWebPGalleryUrl(Media $media): ?string
    {
        if ($media->hasGeneratedConversion('gallery')) {
            return $media->getUrl('gallery');
        }

        return null;
    }

    /**
     * Получить размер файла в читаемом формате
     */
    public static function getFileSize(Media $media): string
    {
        $bytes = $media->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Расширение по сигнатуре файла. null, если это не картинка
     * (например HTML страницы логина Google, сохранённый как .jpg).
     */
    public static function imageExtensionFromContent(string $content): ?string
    {
        if ($content === '') {
            return null;
        }

        if (str_starts_with($content, "\xFF\xD8\xFF")) {
            return 'jpg';
        }

        if (str_starts_with($content, "\x89PNG\r\n\x1a\n")) {
            return 'png';
        }

        if (str_starts_with($content, 'GIF87a') || str_starts_with($content, 'GIF89a')) {
            return 'gif';
        }

        if (strlen($content) >= 12 && substr($content, 0, 4) === 'RIFF' && substr($content, 8, 4) === 'WEBP') {
            return 'webp';
        }

        return null;
    }

    public static function mediaFileIsImage(Media $media): bool
    {
        $path = $media->getPath();
        if (! is_string($path) || ! is_file($path)) {
            return false;
        }

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return false;
        }

        $head = fread($handle, 16);
        fclose($handle);

        return is_string($head) && self::imageExtensionFromContent($head) !== null;
    }

    /**
     * Получить информацию о конверсиях
     */
    public static function getConversionsInfo(Media $media): array
    {
        $conversions = [
            'preview' => $media->hasGeneratedConversion('preview'),
            'thumbnail' => $media->hasGeneratedConversion('thumbnail'),
            'webp' => $media->hasGeneratedConversion('webp'),
            'preview_webp' => $media->hasGeneratedConversion('preview_webp'),
            'gallery' => $media->hasGeneratedConversion('gallery'),
        ];

        return $conversions;
    }
}
