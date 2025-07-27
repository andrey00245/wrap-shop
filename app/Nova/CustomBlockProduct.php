<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class CustomBlockProduct extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\CustomBlockProduct::class;

    public static $title = 'id';

    public static $displayInNavigation = false;

    public static $search = ['id'];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Custom Block', 'customBlock', CustomBlock::class),
            BelongsTo::make('Product', 'product', Product::class),
            Number::make('Порядок', 'sort_order')->sortable(),
        ];
    }
}
