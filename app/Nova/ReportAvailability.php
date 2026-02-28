<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Http\Request;
use Laravel\Nova\Resource;

class ReportAvailability extends Resource
{
    public static $model = \App\Models\ReportAvailability::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'phone', 'email',
    ];

    public static function label()
    {
        return 'Повідомдення про наявність';
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Ім\'я', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Телефон', 'phone')
                ->sortable()
                ->rules('required', 'max:30'),

            Text::make('Email', 'email')
                ->sortable()
                ->rules('nullable', 'email', 'max:255'),

            BelongsTo::make('Товар', 'product', Product::class)
                ->sortable()
                ->searchable()
                ->rules('required'),

            DateTime::make('Дата додавання', 'created_at')
                ->sortable()
                ->readonly()
                ->displayUsing(function ($value) {
                    return $value ? $value->format('d.m.Y H:i') : null;
                }),
        ];
    }
}
