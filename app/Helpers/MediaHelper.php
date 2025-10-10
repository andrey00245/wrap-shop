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
     * Получить информацию о конверсиях
     */
    public static function getConversionsInfo(Media $media): array
    {
        $conversions = [
            'preview' => $media->hasGeneratedConversion('preview'),
            'thumbnail' => $media->hasGeneratedConversion('thumbnail'),
            'gallery' => $media->hasGeneratedConversion('gallery'),
            'webp' => $media->hasGeneratedConversion('webp'),
            'preview_webp' => $media->hasGeneratedConversion('preview_webp'),
        ];

        return $conversions;
    }
}
