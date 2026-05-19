<?php

namespace App\Nova;

use App\Models\KitLine as KitLineModel;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class KitLine extends Resource
{
    use HasSortableRows;

    public static $model = KitLineModel::class;

    public static $displayInNavigation = false;

    public static $title = 'product_display_name';

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Товари набору';
    }

    public static function singularLabel(): string
    {
        return 'товар';
    }

    public static function createButtonLabel(): string
    {
        return 'Додати товар';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Товар', 'product', Product::class)
                ->searchable()
                ->rules('required'),

            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->sortable()
                ->rules('nullable', 'integer', 'min:0'),
        ];
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        return static::redirectToParent($request, $resource);
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        return static::redirectToParent($request, $resource);
    }

    private static function redirectToParent(NovaRequest $request, $resource): string
    {
        if (filled($request->viaResource) && filled($request->viaResourceId)) {
            return '/resources/'.$request->viaResource.'/'.$request->viaResourceId;
        }

        /** @var KitLineModel $line */
        $line = $resource->resource;

        if ($line->kit_group_id) {
            return '/resources/'.KitGroup::uriKey().'/'.$line->kit_group_id;
        }

        return '/resources/'.Kit::uriKey().'/'.$line->kit_id;
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
