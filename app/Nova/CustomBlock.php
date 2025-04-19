<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Database\Eloquent\Builder;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomBlock extends Resource
{
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
        'id',
    ];

    public static function relatableProducts(NovaRequest $request, $query)
    {
        $customBlockId = $request->resourceId;

        if ($customBlockId) {
            return $query->whereDoesntHave('customBlocks', function (Builder $q) use ($customBlockId) {
                $q->where('custom_block_id', $customBlockId);
            });
        }

        return $query;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            NovaTabTranslatable::make([
                Text::make('Назва', 'name'),
            ]),
            BelongsToMany::make('Products')->searchable(),
            Text::make('Url')->hideFromIndex(),
            Boolean::make('Active', 'is_active'),

            Images::make('Баннер','main')
                ->conversionOnIndexView('preview'),

            Text::make('Кількість товарів', function () {
                return $this->products()->count();
            })->onlyOnIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
