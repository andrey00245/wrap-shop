<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PrivacyPolicy extends Model
{
    use HasFactory,
      HasTranslations;

  protected $translatable = ['h1', 'meta_title', 'meta_description', 'content'];

  protected $casts = [
    'h1' => 'json',
    'meta_title' => 'json',
    'meta_description' => 'json',
    'content' => 'json',
  ];
}
