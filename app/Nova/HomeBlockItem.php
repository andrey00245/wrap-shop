<?php

namespace App\Nova;

use App\Enums\HomeBlockType;
use App\Models\HomeBlock as HomeBlockModel;
use App\Models\HomeBlockItem as HomeBlockItemModel;
use App\Nova\Fields\Images;
use App\Nova\Fields\NovaTabTranslatable;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
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
        $t = $this->resolveHomeBlockType($request);
        $isCategories = $t === HomeBlockType::Categories;
        $isProducts = $t === HomeBlockType::Products;
        $isBanner = $t === HomeBlockType::Banner;

        if ($t === null) {
            return [
                ID::make()->sortable(),
                BelongsTo::make('Блок', 'homeBlock', HomeBlock::class)
                    ->rules('required')
                    ->searchable()
                    ->help('Спочатку оберіть блок — тоді з’являться поля для відповідного типу.'),
                Number::make('Порядок', 'sort_order')
                    ->default(0)
                    ->sortable()
                    ->hideFromIndex()
                    ->rules('nullable', 'integer', 'min:0'),
            ];
        }

        $base = [
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

            Number::make('Порядок', 'sort_order')
                ->default(0)
                ->sortable()
                ->hideFromIndex()
                ->rules('nullable', 'integer', 'min:0'),
        ];

        if ($isCategories) {
            return array_merge($base, $this->fieldsForCategoriesBlock());
        }

        if ($isProducts) {
            return array_merge($base, $this->fieldsForProductsBlock());
        }

        if ($isBanner) {
            return array_merge($base, $this->fieldsForBannerBlock());
        }

        return array_merge($base, [
            Text::make('Примітка', fn () => 'Для цього типу блоку поля елемента не налаштовані в Nova.')
                ->onlyOnDetail(),
        ]);
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel>
     */
    private function fieldsForCategoriesBlock(): array
    {
        return [
            BelongsTo::make('Категорія', 'category', Category::class)
                ->nullable()
                ->help('Плитка на головній веде в каталог цієї категорії. Перша за порядком — велика, решта — малі.'),

            NovaTabTranslatable::make([
                Text::make('Заголовок', 'custom_title')
                    ->help('Якщо порожньо — береться назва категорії.'),
            ])->setTitle('Текст на плитці'),

            Images::make('Зображення', 'custom_image')
                ->singleMediaRules('image'),

            HasMany::make('Підкатегорії на плитці', 'quickLinks', HomeBlockItemQuickLink::class),
        ];
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel>
     */
    private function fieldsForProductsBlock(): array
    {
        return [
            BelongsTo::make('Товар', 'product', Product::class)
                ->searchable()
                ->rules('required'),

            NovaTabTranslatable::make([
                Text::make('Заголовок', 'custom_title')
                    ->help('Якщо порожньо — береться назва товару.'),
                Text::make('Підзаголовок', 'custom_tagline')
                    ->help('Якщо порожньо — береться назва категорії товару.'),
            ])->setTitle('Перевизначення'),

            Images::make('Зображення', 'custom_image')
                ->singleMediaRules('image'),
        ];
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel>
     */
    private function fieldsForBannerBlock(): array
    {
        return [
            BelongsTo::make('Товар (посилання)', 'product', Product::class)
                ->searchable()
                ->nullable()
                ->help('Якщо обрано — клік веде на картку товару.'),

            BelongsTo::make('Категорія (посилання)', 'category', Category::class)
                ->nullable()
                ->help('Якщо товар не обрано — можна вести в каталог категорії.'),

            NovaTabTranslatable::make([
                Text::make('Заголовок на слайді', 'custom_title'),
                Text::make('Текст кнопки', 'custom_tagline')
                    ->help('Якщо порожньо — буде «Переглянути» (локалізація).'),
            ])->setTitle('Текст'),

            Images::make('Зображення', 'custom_image')
                ->singleMediaRules('image')
                ->help('Завантажуйте рівно 1325×541 px (пропорція 2.45:1). Тоді банер заповнить блок без полос і без обрізки. Інша пропорція — система обріже по центру.'),
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
        $blockId = static::homeBlockIdFromViaResource($request);

        if (! $blockId && filled($request->resourceId) && $request->route('resource') === static::uriKey()) {
            $blockId = HomeBlockItemModel::query()->find($request->resourceId)?->home_block_id;
        }

        if (! $blockId && filled($request->input('home_block'))) {
            $blockId = (int) $request->input('home_block');
        }

        if (! $blockId && filled($request->input('home_block_id'))) {
            $blockId = (int) $request->input('home_block_id');
        }

        if ($blockId) {
            $type = HomeBlockModel::query()->find($blockId)?->getTypeEnum();
            if ($type !== null) {
                return $type;
            }
        }

        // Update.vue: GET …/{viaResource}/field/{viaRelationship}?relatable=true без viaResourceId.
        if ($request->boolean('relatable')) {
            $fromField = static::homeBlockTypeFromRelatableFieldName((string) ($request->route('field') ?? ''));
            if ($fromField !== null) {
                return $fromField;
            }
        }

        return null;
    }

    /**
     * Коли Nova шукає RelatableField на батьківському ресурсі, контексту item/block може не бути в query.
     */
    private static function homeBlockTypeFromRelatableFieldName(string $fieldAttribute): ?HomeBlockType
    {
        return match ($fieldAttribute) {
            'quickLinks' => HomeBlockType::Categories,
            default => null,
        };
    }

    /**
     * Для HasMany (kitLines, kitGroups, …) viaResource = home-block-items, viaResourceId = id елемента.
     * Для типізованих блоків viaResourceId = id home_blocks.
     */
    private static function homeBlockIdFromViaResource(NovaRequest $request): ?int
    {
        $via = (string) ($request->viaResource ?? '');
        if ($via === '' || ! filled($request->viaResourceId)) {
            return null;
        }

        if ($via === static::uriKey()) {
            $itemId = (int) $request->viaResourceId;

            return (int) (HomeBlockItemModel::query()->whereKey($itemId)->value('home_block_id') ?? 0) ?: null;
        }

        if (in_array($via, [
            HomeBlock::uriKey(),
            HomeBlockCategories::uriKey(),
            Kit::uriKey(),
            HomeBlockSeasonalProducts::uriKey(),
            HomeBlockBanners::uriKey(),
        ], true)) {
            return (int) $request->viaResourceId;
        }

        return null;
    }

    private static function redirectToHomeBlock(NovaRequest $request, ?int $blockId): string
    {
        if (! $blockId) {
            $blockId = static::homeBlockIdFromViaResource($request);
        }

        if ($blockId) {
            $block = HomeBlockModel::query()->find($blockId);
            $uriKey = $block
                ? static::novaUriKeyForHomeBlockType($block->getTypeEnum())
                : HomeBlock::uriKey();

            return '/resources/'.$uriKey.'/'.$blockId;
        }

        return '/resources/'.HomeBlockCategories::uriKey();
    }

    private static function novaUriKeyForHomeBlockType(HomeBlockType $type): string
    {
        return match ($type) {
            HomeBlockType::Categories => HomeBlockCategories::uriKey(),
            HomeBlockType::Products => HomeBlockSeasonalProducts::uriKey(),
            HomeBlockType::Banner => HomeBlockBanners::uriKey(),
            default => HomeBlock::uriKey(),
        };
    }
}
