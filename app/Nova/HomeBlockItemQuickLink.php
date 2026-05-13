<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class HomeBlockItemQuickLink extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\HomeBlockItemQuickLink::class;

    public static $displayInNavigation = false;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Швидкі посилання (підкатегорії)';
    }

    public static function singularLabel(): string
    {
        return 'Посилання на плитці';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Підпис', function () {
                return $this->resource->displayTitle();
            })->onlyOnIndex(),

            BelongsTo::make('Елемент блоку', 'homeBlockItem', HomeBlockItem::class)
                ->rules('required')
                ->searchable(),

            BelongsTo::make('Категорія', 'category', Category::class)->nullable(),

            NovaTabTranslatable::make([
                Text::make('Свій заголовок', 'custom_title'),
            ])->setTitle('Перевизначення'),

            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->sortable()
                ->hideFromIndex()
                ->help('За замовчуванням 0. У списку «Підкатегорії на плитці» на картці елемента блоку порядок можна міняти перетягуванням рядків.')
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
}
