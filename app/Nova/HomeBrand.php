<?php

namespace App\Nova;

use App\Models\HomeBrand as HomeBrandModel;
use App\Nova\Fields\Images;
use App\Support\BrandRegistry;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HomeBrand extends Resource
{
    public static $model = HomeBrandModel::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'brand_key',
        'name',
    ];

    public static function label()
    {
        return 'Бренди';
    }

    public static function singularLabel()
    {
        return 'Бренд';
    }

    public function fields(NovaRequest $request)
    {
        $excludeKeys = HomeBrandModel::query()
            ->when($request->resourceId, fn ($query) => $query->where('id', '!=', $request->resourceId))
            ->pluck('brand_key')
            ->filter()
            ->all();

        return [
            ID::make()->sortable(),

            Select::make('Бренд (з атрибутів товарів)', 'brand_key')
                ->options(BrandRegistry::optionsForNova($excludeKeys))
                ->displayUsingLabels()
                ->searchable()
                ->rules(
                    'required',
                    'max:191',
                    Rule::unique('home_brands', 'brand_key')->ignore($request->resourceId),
                )
                ->help('Список з унікальних значень атрибута brand у товарів (3M, Hexis тощо). Дублі з різним регістром обʼєднуються автоматично.'),

            Text::make('Назва для відображення', 'name')
                ->readonly()
                ->exceptOnForms(),

            Number::make('Порядок', 'sort_order')
                ->sortable()
                ->rules('required', 'integer', 'min:0')
                ->default(0)
                ->help('Порядок на головній сторінці. Логотип доступний на всіх сторінках за значенням бренду товару.'),

            Images::make('Логотип', 'main')
                ->singleImageRules(['required'])
                ->help('Завантажуйте PNG/SVG з прозорим фоном, обрізаний по логотипу (рекомендовано ~200×60 px). Великий холст з полями (наприклад 1000×700) у блоці 90×32 px зробить логотип мікроскопічним.'),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}
