<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VideoCategory extends Model
{
    use HasFactory,
        HasTranslations;

    protected $translatable = ['name'];

    protected $casts = [
        'name' => 'json',
    ];

    /**
     * Связь с видео
     */
    public function videos()
    {
        return $this->hasMany(Video::class, 'category_id');
    }
}
