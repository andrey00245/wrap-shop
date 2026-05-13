<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'youtube_url',
        'youtube_id',
        'sort_order',
    ];

    /**
     * Получить ID видео из YouTube URL
     */
    public static function extractYoutubeId(string $url): ?string
    {
        // Поддерживаем разные форматы YouTube URL:
        // https://www.youtube.com/watch?v=VIDEO_ID
        // https://youtu.be/VIDEO_ID
        // https://www.youtube.com/embed/VIDEO_ID
        // https://youtube.com/watch?v=VIDEO_ID
        
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    /**
     * Получить embed URL для YouTube
     */
    public function getEmbedUrl(): string
    {
        $id = $this->youtube_id ?: self::extractYoutubeId($this->youtube_url);
        return $id ? "https://www.youtube.com/embed/{$id}?autoplay=1&rel=0&modestbranding=1" : $this->youtube_url;
    }

    /**
     * Получить thumbnail URL для YouTube
     */
    public function getThumbnailUrl(): string
    {
        $id = $this->youtube_id ?: self::extractYoutubeId($this->youtube_url);
        return $id ? "https://img.youtube.com/vi/{$id}/maxresdefault.jpg" : '';
    }

    /**
     * Связь с продуктом
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Boot метод для автоматического извлечения YouTube ID
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($video) {
            if (!$video->youtube_id && $video->youtube_url) {
                $video->youtube_id = self::extractYoutubeId($video->youtube_url);
            }
        });
    }
}
