<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use App\Nova\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Implementation extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Implementation>
     */
    public static $model = \App\Models\Implementation::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    public static function label()
    {
        return 'Наші Реалізації';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Назва', 'title'),
            NovaTabTranslatable::make([
                Trix::make('Опис', 'descriptions'),
            ])->hideFromIndex(),
            Date::make('Дата', 'data')->nullable(),
            Images::make('Фото', 'main')
                ->required()
                ->help('Можна завантажити кілька зображень, але відображатиметься тільки перше'),
            Boolean::make('Активний', 'is_active'),
            BelongsTo::make('Продукт', 'product', Product::class)->searchable(),

            Number::make('Порядок сортування', 'sort_order')
                ->sortable()
                ->rules('required', 'integer', 'min:0'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
