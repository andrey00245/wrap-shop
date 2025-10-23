<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Mostafaznv\NovaCkEditor\CkEditor;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class PaymentOption extends Resource
{
    use HasSortableRows;

    public static string $model = \App\Models\PaymentOption::class;

    public static $title = 'name';

    public static $displayInNavigation = true;

    public static function label()
    {
        return 'Опції оплати';
    }

    public static function singularLabel()
    {
        return 'Опція оплати';
    }

    public static $search = [
        'name'
    ];


    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            NovaTabTranslatable::make([
                Text::make('Назва', 'name')
                    ->rules('required', 'max:255')
                    ->sortable(),

                CkEditor::make('Опис', 'description')
                    ->rules('required')
                    ->hideFromIndex(),
            ])->hideFromIndex(),

            Text::make('Назва', 'name')
                ->displayUsing(function ($value) {
                    return $this->getTranslation('name', app()->getLocale());
                })
                ->onlyOnIndex()
                ->sortable(),

            Images::make('Іконка', 'icon'),

            Boolean::make('Активний', 'is_active')
                ->default(true),

            Number::make('Порядок сортування', 'sort_order')
                ->sortable()
                ->rules('required', 'integer', 'min:0'),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }

    public static function canSort(NovaRequest $request)
    {
        return true;
    }
}
