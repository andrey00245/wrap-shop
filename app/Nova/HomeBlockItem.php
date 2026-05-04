<?php

namespace App\Nova;

use App\Enums\HomeBlockItemTileSize;
use App\Enums\HomeBlockType;
use App\Models\HomeBlock as HomeBlockModel;
use App\Models\HomeBlockItem as HomeBlockItemModel;
use App\Nova\Fields\Images;
use App\Nova\Fields\NovaTabTranslatable;
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
        return 'Товар';
    }

    public function fields(NovaRequest $request): array
    {
        $resolvedType = $this->resolveHomeBlockType($request);
        $isProductsType = $resolvedType === HomeBlockType::Products;
        $isCategoriesType = in_array($resolvedType, [HomeBlockType::Categories, HomeBlockType::Kits], true);

        return [
            ID::make()->sortable(),

            Text::make('Назва', function () {
                $title = $this->resource->getTranslation('custom_title', 'uk')
                    ?: $this->resource->getTranslation('custom_title', app()->getLocale())
                    ?: '';

                return trim((string) $title) !== '' ? $title : '—';
            })->onlyOnIndex(),

            BelongsTo::make('Блок', 'homeBlock', HomeBlock::class)
                ->rules('required')
                ->searchable()
                ->readonly(),

            BelongsTo::make('Категорія', 'category', Category::class)
                ->nullable()
                ->canSee(fn () => ! $isProductsType),

            BelongsTo::make('Товар', 'product', Product::class)
                ->searchable()
                ->nullable()
                ->canSee(fn () => ! $isCategoriesType),

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
                Text::make('Заголовок', 'custom_title')
                    ->help('Якщо порожньо — береться назва товару (або категорії).'),
                Text::make('Підзаголовок', 'custom_tagline')
                    ->help('Якщо порожньо — береться назва категорії товару.'),
            ])->setTitle('Перевизначення'),

            Images::make('Зображення', 'custom_image')
                ->singleMediaRules('image'),

            HasMany::make('Підкатегорії на плитці', 'quickLinks', HomeBlockItemQuickLink::class)
                ->canSee(fn () => ! $isProductsType),

            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->sortable()
                ->hideFromIndex()
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
        return [];
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource): string
    {
        $blockId = $resource?->home_block_id ?? null;

        return static::redirectToHomeBlock($request, $blockId);
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource): string
    {
        $blockId = $resource?->home_block_id ?? null;

        return static::redirectToHomeBlock($request, $blockId);
    }

    private function resolveHomeBlockType(NovaRequest $request): ?HomeBlockType
    {
        $blockId = null;

        if ($request->viaResource === HomeBlock::uriKey() && filled($request->viaResourceId)) {
            $blockId = (int) $request->viaResourceId;
        }

        if (! $blockId && filled($request->resourceId) && $request->route('resource') === static::uriKey()) {
            $blockId = HomeBlockItemModel::query()->find($request->resourceId)?->home_block_id;
        }

        if (! $blockId && filled($request->input('home_block'))) {
            $blockId = (int) $request->input('home_block');
        }

        if (! $blockId && filled($request->input('home_block_id'))) {
            $blockId = (int) $request->input('home_block_id');
        }

        if (! $blockId) {
            return null;
        }

        return HomeBlockModel::query()->find($blockId)?->getTypeEnum();
    }

    private static function redirectToHomeBlock(NovaRequest $request, ?int $blockId): string
    {
        if (! $blockId && filled($request->viaResourceId) && $request->viaResource === HomeBlock::uriKey()) {
            $blockId = (int) $request->viaResourceId;
        }

        if ($blockId) {
            return '/resources/'.HomeBlock::uriKey().'/'.$blockId;
        }

        return '/resources/'.HomeBlock::uriKey();
    }
}
