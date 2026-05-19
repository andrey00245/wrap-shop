<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Translatable\HasTranslations;

class KitGroup extends Model implements Sortable
{
    use HasTranslations;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    public array $translatable = ['title'];

    protected $fillable = [
        'kit_id',
        'title',
        'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(static function () {
            HomeIndexCache::flush();
        });

        static::deleted(static function () {
            HomeIndexCache::flush();
        });
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('kit_id', $this->kit_id);
    }

    public function kit(): BelongsTo
    {
        return $this->belongsTo(Kit::class);
    }

    public function kitLines(): HasMany
    {
        return $this->hasMany(KitLine::class)->orderBy('sort_order');
    }

    public function getTitleForNovaAttribute(): string
    {
        $title = $this->getTranslation('title', 'uk') ?: $this->getTranslation('title', app()->getLocale()) ?: '';

        return trim((string) $title) !== '' ? $title : 'Група #'.$this->id;
    }
}
