<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    public array $translatable = [
        'address',
        'video_banner_title',
        'video_banner_desc',
        'slogan_title',
        'slogan_desc',
        'product_meta_title_template',
        'product_meta_description_template',
    ];

    protected $fillable = [
        'phone',
        'phone_view',
        'phone_aditional',
        'phone_aditional_view',
        'telegram',
        'instagram',
        'email',
        'address',
        'google_map_link',
        'video_banner_title',
        'video_banner_desc',
        'slogan_title',
        'slogan_desc',
        'head_code',
        'product_meta_title_template',
        'product_meta_description_template',
    ];
}
