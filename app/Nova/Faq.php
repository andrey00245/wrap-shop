<?php

namespace App\Nova;

use App\Models\Language;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Spatie\NovaTranslatable\NovaTranslatable;

class Faq extends Resource
{
    public static $model = \App\Models\Faq::class;

    public static $title = 'id';

    public static $search = [
        'id',
        'question',
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
            ID::make()->sortable()->hideFromIndex(),

            Number::make('Порядок', 'order')
                ->sortable()
                ->rules('required', 'integer', 'min:0')
                ->hideFromIndex(),

            Boolean::make('Активно', 'is_active')
                ->default(true)
                ->sortable()
                ->hideFromIndex(),

            NovaTabTranslatable::make([
                Text::make('Вопрос', 'question')
                    ->rules('required')
                    ->hideFromIndex(),
            ]),

            // Поля для отображения в индексе
            Text::make('Вопрос (Українська)', 'question->uk')
                ->onlyOnIndex()
                ->hideFromDetail(),

            Text::make('Вопрос (English)', 'question->en')
                ->onlyOnIndex()
                ->hideFromDetail(),

            Text::make('Вопрос (Русский)', 'question->ru')
                ->onlyOnIndex()
                ->hideFromDetail(),

            NovaTabTranslatable::make([
                Trix::make('Ответ', 'answer')
                    ->rules('required')
                    ->hideFromIndex(),
            ]),

            // Поля для отображения ответов в индексе
            Text::make('Ответ (Українська)', 'answer->uk')
                ->onlyOnIndex()
                ->hideFromDetail(),

            Text::make('Ответ (English)', 'answer->en')
                ->onlyOnIndex()
                ->hideFromDetail(),

            Text::make('Ответ (Русский)', 'answer->ru')
                ->onlyOnIndex()
                ->hideFromDetail(),

            // Поля для отображения в индексе
            Text::make('Создано', function () {
                return $this->created_at ? $this->created_at->format('d.m.Y H:i') : '-';
            })->onlyOnIndex()
                ->hideFromDetail(),

            Text::make('Обновлено', function () {
                return $this->updated_at ? $this->updated_at->format('d.m.Y H:i') : '-';
            })->onlyOnIndex()
                ->hideFromDetail(),

            // Поле для отображения статуса активности в индексе
            Boolean::make('Активно', 'is_active')
                ->onlyOnIndex()
                ->hideFromDetail(),

            // Поле для отображения порядка в индексе
            Number::make('Порядок', 'order')
                ->onlyOnIndex()
                ->hideFromDetail(),

            // Поле для отображения ID в индексе
            ID::make('ID')
                ->onlyOnIndex()
                ->hideFromDetail(),
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