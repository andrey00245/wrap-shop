<?php

namespace App\Nova;

use App\Enums\HomeBlockType;
use App\Nova\Fields\NovaTabTranslatable;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class HomeBlock extends Resource
{
    public static $model = \App\Models\HomeBlock::class;

    /** Службовий ресурс (BelongsTo, прямі посилання); у меню — типізовані ресурси під «Блоки головної». */
    public static $displayInNavigation = false;

    public static $title = 'title_for_nova';

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

            Text::make('Заголовок', function () {
                $raw = $this->resource->getTranslation('title', 'uk')
                    ?: $this->resource->getTranslation('title', app()->getLocale());
                $plain = trim(strip_tags((string) $raw));

                return $plain !== '' ? $plain : '—';
            })->onlyOnIndex(),

            NovaTabTranslatable::make([
                Text::make('Заголовок секції', 'title')
                    ->help('Можна HTML для акценту, напр. &lt;span class="colord"&gt;Категорії&lt;/span&gt; товарів'),
            ])
                ->setTitle('Заголовок')
                ->hideFromIndex(),

            Select::make('Тип', 'type')
                ->options([
                    HomeBlockType::Categories->value => 'Категорії',
                    HomeBlockType::Products->value => 'Сезонні товари',
                    HomeBlockType::Banner->value => 'Банер',
                ])
                ->rules('required')
                ->displayUsingLabels(),

            Boolean::make('Активний', 'is_active'),

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
