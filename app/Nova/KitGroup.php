<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class KitGroup extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\KitGroup::class;

    public static $title = 'title_for_nova';

    public static $displayInNavigation = false;

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Підгрупи набору';
    }

    public static function singularLabel(): string
    {
        return 'Підгрупа';
    }

    public static function uriKey(): string
    {
        return 'kit-groups';
    }

    public function fields(NovaRequest $request): array
    {
        $locales = config('tab-translatable.locales', ['uk', 'ru', 'en']);

        return [
            ID::make()->sortable()->hideWhenCreating(),

            BelongsTo::make('Набір', 'kit', Kit::class)
                ->searchable()
                ->hideWhenCreating(fn (NovaRequest $request) => static::viaKitGroups($request))
                ->readonly(fn (NovaRequest $request) => static::viaKitGroups($request))
                ->rules(fn (NovaRequest $request) => static::viaKitGroups($request) ? [] : ['required']),

            Text::make('Назва', 'title_for_nova')
                ->onlyOnIndex(),

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
                ->rules('nullable', 'integer', 'min:0'),

            Panel::make('Товари підгрупи', [
                HasMany::make('Товари', 'kitLines', KitLine::class)
                    ->singularLabel('товар'),
            ]),
        ];
    }

    private static function viaKitGroups(NovaRequest $request): bool
    {
        return filled($request->viaResource)
            && filled($request->viaResourceId)
            && $request->viaRelationship === 'kitGroups';
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        return '/resources/'.static::uriKey().'/'.$resource->getKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        return '/resources/'.static::uriKey().'/'.$resource->getKey();
    }

    public static function redirectToKit(?int $kitId): string
    {
        return $kitId
            ? '/resources/'.Kit::uriKey().'/'.$kitId
            : '/resources/'.Kit::uriKey();
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
