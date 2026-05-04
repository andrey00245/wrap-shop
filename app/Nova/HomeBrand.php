<?php

namespace App\Nova;

use App\Nova\Fields\Images;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HomeBrand extends Resource
{
    public static $model = \App\Models\HomeBrand::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
    ];

    public static function label()
    {
        return 'Бренди (Головна)';
    }

    public static function singularLabel()
    {
        return 'Бренд (Головна)';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Назва', 'name')
                ->rules('nullable', 'max:255'),

            Number::make('Порядок', 'sort_order')
                ->sortable()
                ->rules('required', 'integer', 'min:0')
                ->default(0),

            Images::make('Логотип', 'main')
                ->singleImageRules(['required']),
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
