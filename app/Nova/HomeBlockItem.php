<?php

namespace App\Nova;

use App\Enums\HomeBlockItemTileSize;
use App\Enums\HomeBlockType;
use App\Models\HomeBlock as HomeBlockModel;
use App\Models\HomeBlockItem as HomeBlockItemModel;
use App\Nova\Actions\SyncHomeBlockItemChildQuickLinks;
use App\Nova\Fields\NovaTabTranslatable;
use App\Nova\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class HomeBlockItem extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\HomeBlockItem::class;

    public static $displayInNavigation = false;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return 'Елементи блоку головної';
    }

    public static function singularLabel(): string
    {
        return 'Елемент блоку';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Категорія → підпис', function () {
                $cat = $this->resource->category?->getTranslation('name', 'uk') ?? '—';
                $title = $this->resource->displayTitle();

                return $cat.' → '.$title;
            })->onlyOnIndex(),

            BelongsTo::make('Блок', 'homeBlock', HomeBlock::class)
                ->rules('required')
                ->searchable(),

            BelongsTo::make('Категорія', 'category', Category::class)->nullable(),

            Select::make('Розмір плитки', 'tile_size')
                ->options([
                    HomeBlockItemTileSize::Large->value => 'Велика (колонка героя)',
                    HomeBlockItemTileSize::Small->value => 'Мала (сітка поруч)',
                ])
                ->nullable()
                ->displayUsingLabels()
                ->hideFromIndex()
                ->help('Показується лише для блоку типу «Категорії»: одна «Велика» — у широку колонку, решта — у сітку; якщо ніде не обрано «Велика», великою буде перший за порядком.')
                ->dependsOn(
                    ['home_block_id', 'resource:'.HomeBlock::uriKey()],
                    function (Select $field, NovaRequest $request, FormData $formData) {
                        $blockId = $formData->get('home_block_id');
                        if (blank($blockId)) {
                            $blockId = $formData->get('resource:'.HomeBlock::uriKey());
                        }
                        if (blank($blockId) && filled($request->resourceId)
                            && $request->route('resource') === static::uriKey()) {
                            $blockId = HomeBlockItemModel::query()->find($request->resourceId)?->home_block_id;
                        }

                        if (blank($blockId)) {
                            $field->hide();

                            return;
                        }

                        $block = HomeBlockModel::query()->find($blockId);
                        if ($block === null || $block->getTypeEnum() !== HomeBlockType::Categories) {
                            $field->hide();
                        }
                    }
                ),

            NovaTabTranslatable::make([
                Text::make('Свій заголовок', 'custom_title')
                    ->help('Якщо порожньо — береться назва категорії з CRM'),
            ])->setTitle('Перевизначення'),

            Images::make('Своє зображення', 'custom')
                ->help('Якщо не завантажити — зображення з категорії (CRM).'),

            HasMany::make('Підкатегорії на плитці', 'quickLinks', HomeBlockItemQuickLink::class),

            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->sortable()
                ->hideFromIndex()
                ->help('За замовчуванням 0. На сторінці блоку головної («Елементи») порядок можна міняти перетягуванням рядків.')
                ->rules('nullable', 'integer', 'min:0'),
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
        return [
            new SyncHomeBlockItemChildQuickLinks,
        ];
    }
}
