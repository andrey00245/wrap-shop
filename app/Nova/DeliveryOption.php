<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Mostafaznv\NovaCkEditor\CkEditor;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class DeliveryOption extends Resource
{
    use HasSortableRows;

    public static string $model = \App\Models\DeliveryOption::class;

    public static $title = 'name';

    public static $displayInNavigation = true;

    public static function label()
    {
        return 'Опції доставки';
    }

    public static function singularLabel()
    {
        return 'Опція доставки';
    }

    public static $search = [
        'name',
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
                ->displayUsing(fn ($value) => $this->getTranslation('name', app()->getLocale()))
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
}
