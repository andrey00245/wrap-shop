<?php

namespace App\Nova;

use App\Models\Kit as KitModel;
use App\Nova\Fields\NovaTabTranslatable;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Kit extends Resource
{
    use HasSortableRows;

    public static $model = KitModel::class;

    public static $title = 'title_for_nova';

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Набори (kits)';
    }

    public static function singularLabel(): string
    {
        return 'Набір';
    }

    public static function uriKey(): string
    {
        return 'kits';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Boolean::make('Активний', 'is_active'),

            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->sortable()
                ->hideFromIndex()
                ->rules('nullable', 'integer', 'min:0'),

            NovaTabTranslatable::make([
                Text::make('Заголовок', 'title')
                    ->help('Назва на картці набору.'),
                Text::make('Лейбл (рядок над назвою)', 'tagline')
                    ->help('Наприклад STARTER KIT.'),
                Textarea::make('Опис', 'description')
                    ->rows(4)
                    ->nullable(),
            ])->setTitle('Картка набору'),

            Panel::make('Склад набору', [
                HasMany::make('Підгрупи', 'kitGroups', KitGroup::class),

                HasMany::make('Основні товари', 'ungroupedKitLines', KitLine::class)
                    ->singularLabel('товар'),
            ]),
        ];
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        return '/resources/'.static::uriKey().'/'.$resource->getKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        return '/resources/'.static::uriKey().'/'.$resource->getKey();
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
