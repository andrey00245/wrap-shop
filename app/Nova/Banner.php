<?php

namespace App\Nova;

use App\Nova\Fields\Images;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Banner extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Banner>
     */
    public static $model = \App\Models\Banner::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';


    public static function label()
    {
        return 'Банери';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'url', 'nav_title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Images::make( 'Фото','main')
                ->conversionOnIndexView('preview'),
            Text::make('Силка банера','url')
                ->displayUsing(function ($value) {
                    return $value ? (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) : '';
                }),
            Text::make('Назва в навігації', 'nav_title')
                ->help('Короткий текст під банером: Новини, Знижка до 50%, Захисне скло тощо.')
                ->rules('nullable', 'max:255'),
            Text::make('Текст кнопки', 'button_text')
                ->help('Наприклад: Перейти до каталогу. Якщо порожньо — кнопка не показується.')
                ->rules('nullable', 'max:255'),
            Text::make('Силка кнопки', 'button_url')
                ->help('Якщо порожньо — для кнопки використається силка банера.')
                ->rules('nullable', 'max:255'),

            Number::make('Позиція','position')->sortable(),
            Boolean::make('Активний','is_active')->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
