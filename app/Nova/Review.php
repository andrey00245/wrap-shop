<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Review extends Resource
{
    /**
     * Модель, до якої прив'язаний ресурс.
     *
     * @var class-string<\App\Models\Review>
     */
    public static $model = \App\Models\Review::class;

    /**
     * Поле, яке використовується для представлення ресурсу.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Колонки, які можна шукати.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'Відгуки';
    }

    /**
     * Отримати поля, що відображаються в ресурсі.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Продукт', 'product', Product::class),
            Text::make("Ім'я", 'name')->sortable(),
            Textarea::make('Відгук', 'text')->alwaysShow(),
            Number::make('Оцінка', 'rating')->min(1)->max(5)->step(1)->sortable(),
            Boolean::make('Активний', 'is_active')->sortable(),
        ];
    }

    /**
     * Отримати карти, доступні для запиту.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Отримати фільтри, доступні для ресурсу.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Отримати лінзи, доступні для ресурсу.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Отримати дії, доступні для ресурсу.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
