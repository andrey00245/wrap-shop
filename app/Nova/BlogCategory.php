<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class BlogCategory extends Resource
{
    public static $model = \App\Models\BlogCategory::class;

    public static $title = 'id';

    public static $search = [
        'id',
        'name->uk',
        'name->ru',
        'name->en',
    ];

    public static function label()
    {
        return 'Блог — категорії';
    }

    public function title()
    {
        $m = $this->resource;
        if (! $m instanceof \App\Models\BlogCategory) {
            return '—';
        }

        $locale = app()->getLocale();
        $name = $m->getTranslation('name', $locale)
            ?: $m->getTranslation('name', 'uk')
            ?: $m->getTranslation('name', config('app.fallback_locale', 'en'));

        return $name !== '' && $name !== null ? (string) $name : ('#'.$m->id);
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            NovaTabTranslatable::make([
                Text::make('Назва', 'name')->rules('required'),
            ]),
            Number::make('Порядок', 'sort_order')->default(0),
            Boolean::make('Активна', 'is_active')->default(true),
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
