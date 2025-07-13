<?php

namespace App\Nova;

use App\Nova\Actions\TranslateProductContent;
use App\Nova\Filters\TranslatedStatusFilter;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Mostafaznv\NovaCkEditor\CkEditor;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Product>
     */
    public static $model = \App\Models\Product::class;

    public static $title = 'name';

    public static function label()
    {
        return 'Продукти';
    }


    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    public static $search = [
        'code',
        'external_code',
        'article',
        'name->en',
        'name->ru',
        'name->uk',
    ];

    public function getNameAttribute()
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public static function relatableVolumeVariants(NovaRequest $request, $query)
    {
        $currentProductId = $request->resourceId;

        return $query
            ->whereHas('attributes', function ($q) {
                $q->where('field_name', 'volume');
            })

            ->where('id', '!=', $currentProductId)

            ->whereDoesntHave('isVolumeVariantOf', function ($q) use ($currentProductId) {
                $q->where('product_id', $currentProductId);
            })

            ->whereDoesntHave('volumeVariants', function ($q) use ($currentProductId) {
                $q->where('variant_product_id', $currentProductId);
            })

            ->whereNotIn('id', function ($sub) use ($currentProductId) {
                $sub->select('variant_product_id')
                    ->from('product_volume_variants')
                    ->where('product_id', $currentProductId);
            })
            ->whereNotIn('id', function ($sub) use ($currentProductId) {
                $sub->select('product_id')
                    ->from('product_volume_variants')
                    ->where('variant_product_id', $currentProductId);
            });
    }

//    public static function searchable(Request $request)
//    {
//        $query = parent::searchable($request);
//
//        // Фильтруем только те товары, которые имеют атрибут "volume"
//        return $query->whereHas('attributes', function ($q) {
//            $q->where('field_name', 'volume');
//        });
//    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            new Panel('Основна інформація', $this->mainInformationFields()),
            new Panel('Фото банер', $this->Banners()),
        ];
    }

    protected function mainInformationFields()
    {
        return [
            NovaTabTranslatable::make([
                Text::make('Назва', 'name'),
                CkEditor::make('Опис', 'descriptions')
                    ->nullable(),
            ])->hideFromIndex(),

            Text::make('Назва', 'name')
                ->displayUsing(function ($value) {
                    return Str::limit($value, 50) . (strlen($value) > 50 ? '...' : '');
                })
                ->onlyOnIndex()
                ->nullable(),

            BelongsTo::make('Категория','category',Category::class)->nullable(),

            Text::make('Арикул', 'article')
                ->nullable(),

            Text::make('Код', 'code')
                ->sortable(),

            Images::make('Фото', 'images')
                ->conversionOnIndexView('preview'),

            Boolean::make('Лідери продажів','is_top_seller')
                ->sortable(),
            Number::make('Кількість','stock')
                ->sortable(),
            Boolean::make('Активний','is_active')
                ->sortable(),
            Boolean::make('Перекладено', function () {
                return $this->is_translated;
            })
                ->trueValue(true)
                ->falseValue(false)
                ->sortable()
                ->onlyOnIndex(),
            HasMany::make('Типи Цін','prices',ProductPrices::class),
            BelongsToMany::make('Атрибуты', 'attributes', Attribute::class)
                ->fields(function () {
                    return [
                        NovaTabTranslatable::make([
                            CkEditor::make('Значение', 'value')
                                ->rules('required'),
                        ])
                    ];
                }),

            BelongsToMany::make('Обʼєми (варіанти)', 'volumeVariants', self::class)
                ->fields(function () {
                    return [];
                })
                ->singularLabel('Обʼєм')
                ->canSee(function () {
                    return $this->hasVolumeAttribute();
                })
                ->searchable()
                ->hideFromIndex()
        ];
    }

    protected function Banners() {
        return [
            NovaTabTranslatable::make([
                Text::make('Назва', 'banner_title'),
            ])->hideFromIndex(),
            Images::make('Фото Вигляду', 'banner_images')
                ->conversionOnIndexView('preview')
            ->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
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
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new TranslatedStatusFilter(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
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
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new TranslateProductContent(),
        ];
    }
}
