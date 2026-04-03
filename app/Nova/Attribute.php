<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Attribute extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Attribute>
     */
    public static $model = \App\Models\Attribute::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public static function label()
    {
        return 'Атрибути';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Determine if the given user can create a resource.
     *
     *
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('ID', 'id')
                ->sortable()
                ->onlyOnIndex(),

            Text::make('Поле', 'field_name')
                ->sortable()
                ->onlyOnIndex(),

            NovaTabTranslatable::make([
                Text::make('Назва', 'name')
                    ->rules('required', 'string', 'max:255')
                    ->help('Обовʼязкове поле'),
            ]),

            Text::make('Назва', 'name')
                ->displayUsing(function ($value) {
                    return \Str::limit($value, 50).(strlen($value) > 50 ? '...' : '');
                })
                ->onlyOnIndex()
                ->sortable(),

            Boolean::make('Видимий', 'is_visible')
                ->sortable()
                ->help('Показувати атрибут на сторінці товару')
                ->default(true),

            Boolean::make('Перекладено', function () {
                return $this->is_translated;
            })
                ->trueValue(true)
                ->falseValue(false)
                ->sortable()
                ->onlyOnIndex(),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new \App\Nova\Actions\TranslateAttributeContent,
        ];
    }
}
