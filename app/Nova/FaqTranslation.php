<?php

namespace App\Nova;

use App\Models\Language;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class FaqTranslation extends Resource
{
    public static $model = \App\Models\FaqTranslation::class;

    public static $title = 'id';

    public static $search = [
        'id',
        'question',
    ];

    public static function label()
    {
        return 'Переводы FAQ';
    }

    public static function singularLabel()
    {
        return 'Перевод FAQ';
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('FAQ', 'faq', Faq::class)
                ->rules('required'),

            Select::make('Язык', 'language_id')
                ->options([
                    Language::LANGUAGE_ID_UK => 'Українська',
                    Language::LANGUAGE_ID_EN => 'English',
                    Language::LANGUAGE_ID_RU => 'Русский',
                ])
                ->rules('required')
                ->sortable(),

            Text::make('Вопрос', 'question')
                ->rules('required', 'max:1000')
                ->hideFromIndex(),

            Trix::make('Ответ', 'answer')
                ->rules('required')
                ->hideFromIndex(),
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