<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class CustomBlockProduct extends Pivot implements Sortable
{
    use SortableTrait;

    protected $table = 'custom_block_product';

    public $incrementing = true;
    public $primaryKey = 'id';

    public $sortable = [
        'order_column_name'  => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function buildSortQuery()
    {
        return static::query()
            ->where('custom_block_id', $this->custom_block_id);
    }
}
