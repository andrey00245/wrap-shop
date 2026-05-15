<?php

namespace App\Nova;

use App\Models\HomeBlockKitLine as HomeBlockKitLineModel;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HomeBlockKitLine extends Resource
{
    public static $model = \App\Models\HomeBlockKitLine::class;

    public static $displayInNavigation = false;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Товари в наборі';
    }

    public static function singularLabel(): string
    {
        return 'Товар у наборі';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Товар', function () {
                return $this->resource->product?->getName() ?? '—';
            })->onlyOnIndex(),

            BelongsTo::make('Елемент блоку', 'homeBlockItem', HomeBlockItem::class)
                ->searchable()
                ->hideWhenCreating(function (NovaRequest $request) {
                    return filled($request->viaResource)
                        && filled($request->viaResourceId)
                        && $request->viaRelationship === 'kitLines';
                })
                ->rules(function (NovaRequest $request) {
                    if (filled($request->viaResource)
                        && filled($request->viaResourceId)
                        && $request->viaRelationship === 'kitLines') {
                        return [];
                    }

                    return ['required'];
                }),

            BelongsTo::make('Товар', 'product', Product::class)
                ->searchable()
                ->rules('required'),

            BelongsTo::make('Група', 'kitGroup', HomeBlockKitGroup::class)
                ->nullable()
                ->relatableQueryUsing(function (NovaRequest $request, $query) {
                    $itemId = null;
                    if (($request->viaResource ?? null) === HomeBlockItem::uriKey() && filled($request->viaResourceId)) {
                        $itemId = (int) $request->viaResourceId;
                    }
                    if ($itemId === null && filled($request->input('home_block_item'))) {
                        $itemId = (int) $request->input('home_block_item');
                    }
                    if ($itemId === null && $request->route('resource') === static::uriKey() && filled($request->resourceId)) {
                        $fromRow = HomeBlockKitLineModel::query()
                            ->whereKey($request->resourceId)
                            ->value('home_block_item_id');
                        $itemId = $fromRow ? (int) $fromRow : null;
                    }

                    return $itemId
                        ? $query->where('home_block_item_id', $itemId)
                        : $query->whereRaw('0 = 1');
                }),

            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->sortable()
                ->hideFromIndex()
                ->rules('nullable', 'integer', 'min:0'),
        ];
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

    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        return static::redirectToHomeBlockItem($resource?->home_block_item_id);
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        return static::redirectToHomeBlockItem($resource?->home_block_item_id);
    }

    private static function redirectToHomeBlockItem(?int $itemId): string
    {
        if ($itemId) {
            return '/resources/'.HomeBlockItem::uriKey().'/'.$itemId;
        }

        return '/resources/'.HomeBlockCategories::uriKey();
    }
}
