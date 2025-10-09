<?php

namespace App\Helpers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageHelper
{
    /**
     * Get optimized image URL with WebP fallback
     */
    public static function getOptimizedUrl(Media $media, string $conversion = '', bool $webp = true): string
    {
        if (!$webp) {
            return $media->getUrl($conversion);
        }

        // Check if WebP version exists
        $webpUrl = $media->getUrl($conversion . '_webp');
        
        // If WebP doesn't exist, return original
        if (!file_exists(public_path(parse_url($webpUrl, PHP_URL_PATH)))) {
            return $media->getUrl($conversion);
        }

        return $webpUrl;
    }

    /**
     * Generate picture element with WebP and fallback
     */
    public static function getPictureElement(Media $media, string $conversion = '', array $attributes = []): string
    {
        $webpUrl = self::getOptimizedUrl($media, $conversion, true);
        $fallbackUrl = $media->getUrl($conversion);
        
        $alt = $attributes['alt'] ?? '';
        $title = $attributes['title'] ?? '';
        $class = $attributes['class'] ?? '';
        $loading = $attributes['loading'] ?? 'lazy';
        $width = $attributes['width'] ?? '';
        $height = $attributes['height'] ?? '';

        $attributesString = '';
        if ($class) $attributesString .= " class=\"{$class}\"";
        if ($loading) $attributesString .= " loading=\"{$loading}\"";
        if ($width) $attributesString .= " width=\"{$width}\"";
        if ($height) $attributesString .= " height=\"{$height}\"";

        return "
        <picture>
            <source srcset=\"{$webpUrl}\" type=\"image/webp\">
            <img src=\"{$fallbackUrl}\" alt=\"{$alt}\" title=\"{$title}\"{$attributesString}>
        </picture>";
    }

    /**
     * Check if browser supports WebP
     */
    public static function supportsWebP(): bool
    {
        if (!isset($_SERVER['HTTP_ACCEPT'])) {
            return false;
        }

        return strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    }
}
