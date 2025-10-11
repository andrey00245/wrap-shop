<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableManyToManyRows;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class CustomBlock extends Resource
{
    use HasSortableManyToManyRows;
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CustomBlock>
     */
    public static $model = \App\Models\CustomBlock::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public static function label()
    {
        return 'Кастомні блоки';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    public static function relatableProducts(NovaRequest $request, $query)
    {
        // Убираем фильтрацию - позволяем видеть все товары, включая уже добавленные
        return $query;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('products');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            NovaTabTranslatable::make([
                Text::make('Назва', 'name')
                    ->help('Для підсвічування тексту використовуйте тег span з класом "colord", наприклад: &lt;span class=&quot;colord&quot;&gt;3M&lt;/span&gt;'),
            ]),
            BelongsToMany::make('Продукти', 'products', Product::class)
                ->fields(function () {
                    return [
                        Number::make('Порядок', 'sort_order')
                            ->sortable()
                            ->rules('required', 'integer', 'min:0'),
                    ];
                })
                ->searchable()
                ->sortable()
                ->showCreateRelationButton(),

            Text::make('Url')->hideFromIndex(),
            Boolean::make('Active', 'is_active'),

            Images::make('Баннер','main'),

            Text::make('Кількість товарів', function () {
                return $this->products()->count();
            })->onlyOnIndex(),

            Number::make('Порядок сортування', 'sort_order')
                ->sortable()
                ->rules('required', 'integer', 'min:0'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public $sortable = [
        'only_sort_on' => \App\Nova\Product::class,
    ];
}
