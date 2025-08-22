<?php

namespace App\Nova;

use App\Models\Language;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Faq extends Resource
{
    public static $model = \App\Models\Faq::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'FAQ';
    }

    public static function singularLabel()
    {
        return 'FAQ';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Number::make('Порядок', 'order')
                ->sortable()
                ->rules('required', 'integer', 'min:0'),

            Boolean::make('Активно', 'is_active')
                ->default(true),

            HasMany::make('Переводы', 'translations', FaqTranslation::class),
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