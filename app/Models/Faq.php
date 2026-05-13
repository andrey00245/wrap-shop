<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasTranslations, SortableTrait;

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

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_faq')
            ->withPivot('sort_order')
            ->orderBy('pivot_sort_order')
            ->orderBy('categories.id');
    }
}
