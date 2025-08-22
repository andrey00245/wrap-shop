<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'order',
        'is_active',
        'question',
        'answer',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'question' => 'json',
        'answer' => 'json',
    ];

    public array $translatable = [
        'question',
        'answer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}