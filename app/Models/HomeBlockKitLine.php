<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class HomeBlockKitLine extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    protected $fillable = [
        'home_block_item_id',
        'home_block_kit_group_id',
        'product_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(static function (HomeBlockKitLine $line) {
            if ($line->home_block_kit_group_id !== null) {
                $group = HomeBlockKitGroup::query()->find($line->home_block_kit_group_id);
                if ($group === null || (int) $group->home_block_item_id !== (int) $line->home_block_item_id) {
                    $line->home_block_kit_group_id = null;
                }
            }
        });

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

    public function kitGroup(): BelongsTo
    {
        return $this->belongsTo(HomeBlockKitGroup::class, 'home_block_kit_group_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
