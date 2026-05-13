<?php

namespace App\Nova;

use App\Enums\HomeBlockType;
use App\Nova\Fields\NovaTabTranslatable;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HomeBlock extends Resource
{
    public static $model = \App\Models\HomeBlock::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Блоки головної';
    }

    public static function singularLabel(): string
    {
        return 'Блок головної';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Заголовок (UK)', function () {
                return $this->resource->getTranslation('title', 'uk') ?: '—';
            })->onlyOnIndex(),

            NovaTabTranslatable::make([
                Text::make('Заголовок секції', 'title')
                    ->help('Можна HTML для акценту, напр. &lt;span class="colord"&gt;Категорії&lt;/span&gt; товарів'),
            ])->setTitle('Заголовок'),

            Select::make('Тип', 'type')
                ->options([
                    HomeBlockType::Categories->value => 'Категорії',
                    HomeBlockType::Kits->value => 'Набори (kits)',
                    HomeBlockType::Products->value => 'Товари',
                    HomeBlockType::Banner->value => 'Банер',
                    HomeBlockType::Custom->value => 'Власний / інше',
                ])
                ->rules('required')
                ->displayUsingLabels(),

            Boolean::make('Активний', 'is_active'),

            Number::make('Порядок', 'sort_order')
                ->sortable()
                ->rules('required', 'integer', 'min:0'),

            HasMany::make('Елементи', 'items', HomeBlockItem::class),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
