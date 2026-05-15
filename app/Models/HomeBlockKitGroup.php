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

class HomeBlockKitGroup extends Model implements Sortable
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
        'home_block_item_id',
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
        return static::query()->where('home_block_item_id', $this->home_block_item_id);
    }

    public function homeBlockItem(): BelongsTo
    {
        return $this->belongsTo(HomeBlockItem::class);
    }

    public function kitLines(): HasMany
    {
        return $this->hasMany(HomeBlockKitLine::class)->orderBy('sort_order');
    }
}
