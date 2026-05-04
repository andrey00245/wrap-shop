<?php

namespace App\Nova;

use App\Nova\Fields\Images;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class YoutubeChannelCard extends Resource
{
    public static $model = \App\Models\YoutubeChannelCard::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'button_text', 'button_url',
    ];

    public static function label()
    {
        return 'YouTube картки';
    }

    public static function singularLabel()
    {
        return 'YouTube картка';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Images::make('Зображення', 'main')
                ->conversionOnIndexView('preview')
                ->singleImageRules(['required']),

            Text::make('Заголовок', 'title')
                ->rules('required', 'max:255'),

            Text::make('Текст кнопки', 'button_text')
                ->rules('required', 'max:255'),

            Text::make('URL кнопки', 'button_url')
                ->rules('required', 'max:2048'),

            Boolean::make('Активна', 'is_active')
                ->default(true),

            Number::make('Порядок', 'sort_order')
                ->sortable()
                ->rules('required', 'integer', 'min:0')
                ->default(0),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}
