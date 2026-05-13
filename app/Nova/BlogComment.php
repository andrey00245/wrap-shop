<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class BlogComment extends Resource
{
    public static $model = \App\Models\BlogComment::class;

    public static $title = 'id';

    public static $search = [
        'id', 'guest_name', 'guest_email',
    ];

    public static function label()
    {
        return 'Блог — коментарі';
    }

    public function title()
    {
        $m = $this->resource;
        if (! $m instanceof \App\Models\BlogComment) {
            return '—';
        }

        $name = $m->guest_name ?: '—';
        $preview = \Illuminate\Support\Str::limit(strip_tags((string) $m->body), 40);

        return $preview !== '' ? "{$name}: {$preview}" : $name;
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Стаття', 'post', BlogPost::class)
                ->searchable()
                ->rules('required'),
            Text::make('Ім’я', 'guest_name')->rules('required', 'max:120'),
            Text::make('E-mail', 'guest_email')->rules('required', 'email', 'max:190'),
            Textarea::make('Текст', 'body')->alwaysShow()->rules('required', 'max:8000'),
            Boolean::make('Схвалено (показувати на сайті)', 'is_approved'),
            Text::make('IP', 'ip_address')->readonly()->hideFromIndex(),
            DateTime::make('Створено', 'created_at'),
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
