<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class ProductVideo extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ProductVideo>
     */
    public static $model = \App\Models\ProductVideo::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'youtube_url';

    public static function label()
    {
        return 'YouTube Відео';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'youtube_url',
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

            BelongsTo::make('Продукт', 'product', Product::class)
                ->searchable()
                ->rules('required'),

            Text::make('YouTube URL', 'youtube_url')
                ->rules('required', 'url')
                ->help('Вставте посилання на YouTube відео (наприклад: https://www.youtube.com/watch?v=VIDEO_ID або https://youtu.be/VIDEO_ID)')
                ->placeholder('https://www.youtube.com/watch?v=VIDEO_ID'),

            Text::make('YouTube ID', 'youtube_id')
                ->readonly()
                ->help('Автоматично витягується з URL'),

            Number::make('Порядок сортування', 'sort_order')
                ->default(0)
                ->sortable()
                ->help('Чим менше число, тим раніше відображається відео'),
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
