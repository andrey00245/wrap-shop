<?php

namespace App\Nova;

use App\Nova\Actions\ApproveReviewAction;
use App\Nova\Actions\RejectReviewAction;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
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
        'id', 'name', 'text',
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
            BelongsTo::make('Продукт (необов’язково)', 'product', Product::class)->nullable(),
            Text::make("Ім'я", 'name')->sortable(),
            Textarea::make('Відгук', 'text')->alwaysShow(),
            Text::make('Фото', function () {
                $url = $this->resource->photoUrl();
                if ($url === null) {
                    return '—';
                }

                return '<a href="'.$url.'" target="_blank" rel="noopener"><img src="'.$url.'" style="width: 72px; height: 72px; object-fit: cover; border-radius: 4px;" alt="Review photo"></a>';
            })->asHtml()->onlyOnIndex(),
            Text::make('Фото URL', function () {
                return $this->resource->photoUrl() ?? '—';
            })->onlyOnDetail(),
            Number::make('Оцінка', 'rating')->min(1)->max(5)->step(1)->sortable(),
            Select::make('Модерація', 'moderation_status')
                ->options([
                    'pending' => 'Очікує',
                    'approved' => 'Схвалено',
                    'rejected' => 'Відхилено',
                ])
                ->displayUsingLabels()
                ->sortable()
                ->rules('required')
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $status = (string) $request->input($requestAttribute, 'pending');
                    $model->{$attribute} = $status;
                    $model->is_active = $status === 'approved';
                    $model->moderated_at = in_array($status, ['approved', 'rejected'], true) ? now() : null;
                }),
            Boolean::make('Активний (показується на сайті)', 'is_active')
                ->readonly()
                ->sortable(),
            DateTime::make('Модеровано', 'moderated_at')->exceptOnForms(),
            DateTime::make('Дата створення', 'created_at')
                ->sortable()
                ->default(now())
                ->help('Можна змінити дату створення відгуку'),
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
        return [
            new ApproveReviewAction,
            new RejectReviewAction,
        ];
    }
}
