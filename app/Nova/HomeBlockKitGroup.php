<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HomeBlockKitGroup extends Resource
{
    public static $model = \App\Models\HomeBlockKitGroup::class;

    public static $displayInNavigation = false;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Групи товарів (набори)';
    }

    public static function singularLabel(): string
    {
        return 'Група в наборі';
    }

    public function fields(NovaRequest $request): array
    {
        $locales = config('tab-translatable.locales', ['uk', 'ru', 'en']);

        return [
            ID::make()->sortable()->hideWhenCreating(),

            Text::make('Група', function () {
                $t = $this->resource->getTranslation('title', 'uk')
                    ?: $this->resource->getTranslation('title', app()->getLocale());

                return filled($t) ? $t : '—';
            })->onlyOnIndex(),

            BelongsTo::make('Елемент блоку', 'homeBlockItem', HomeBlockItem::class)
                ->searchable()
                ->hideWhenCreating(function (NovaRequest $request) {
                    return filled($request->viaResource)
                        && filled($request->viaResourceId)
                        && $request->viaRelationship === 'kitGroups';
                })
                ->rules(function (NovaRequest $request) {
                    if (filled($request->viaResource)
                        && filled($request->viaResourceId)
                        && $request->viaRelationship === 'kitGroups') {
                        return [];
                    }

                    return ['required'];
                }),

            Text::make('Назва групи', 'nova_title_label')
                ->rules('required', 'max:500')
                ->hideFromIndex()
                ->resolveUsing(function () use ($locales) {
                    foreach ($locales as $loc) {
                        $t = $this->resource->getTranslation('title', $loc, false);
                        if (filled($t)) {
                            return (string) $t;
                        }
                    }

                    return '';
                })
                ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) use ($locales) {
                    $v = (string) $request->input($requestAttribute, '');
                    foreach ($locales as $loc) {
                        $model->setTranslation('title', $loc, $v);
                    }
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
