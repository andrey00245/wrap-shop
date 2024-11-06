<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    use TranslatableTabToRowTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Category>
     */
    public static $model = \App\Models\Category::class;

    public static $title = 'name';

    public static function label()
    {
        return 'Категорії';
    }

    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            NovaTabTranslatable::make([
                Text::make('Назва','name'),
            ])->setTitle('Назва'),

            BelongsTo::make('Основна Категорія', 'parent', self::class)
                ->nullable()
                ->displayUsing(function ($parent) {
                    return $parent->getNameUkAttribute();
                }),

            HasMany::make('Під Категорія', 'children', self::class),
            HasMany::make('Продукти', 'products', Product::class),

            Images::make('Фото','main')
                ->conversionOnIndexView('preview'),
        ];
    }
}
