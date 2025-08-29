<?php

namespace App\Nova;

use App\Models\Language;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Faq extends Resource
{
    use HasSortableRows;

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
            // Поля для редактирования
            NovaTabTranslatable::make([
                Text::make('Вопрос', 'question')
                    ->rules('required')
                    ->hideFromIndex(),
            ])->hideFromIndex(),

            NovaTabTranslatable::make([
                Trix::make('Ответ', 'answer')
                    ->rules('required')
                    ->hideFromIndex(),
            ])->hideFromIndex(),


            Text::make('Вопрос (Українська)', function () {
                $question = $this->question ?? '';
                return mb_strlen($question) > 80 ? mb_substr($question, 0, 80) . '...' : $question;
            })
                ->onlyOnIndex()
                ->hideFromDetail()
                ->sortable(false),

            Boolean::make('Активно', 'is_active')
                ->default(true)
                ->sortable()
                ->onlyOnIndex(),

            // Поля для отображения в индексе
            Number::make('Порядок', 'order')
                ->sortable()
                ->rules('required', 'integer', 'min:0')
                ->onlyOnIndex(),
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
