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
     * Связь с видеообзорами
     */
    public function videoReviews()
    {
        return $this->hasMany(VideoReview::class, 'category_id');
    }
}
