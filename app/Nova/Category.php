<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
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
        return 'Категорії товарів';
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
                Text::make('Slug','slug'),
            ])->setTitle('Основна інформація'),

            NovaTabTranslatable::make([
                Text::make('Meta Title','meta_title')
                    ->help('Заголовок для SEO (до 60 символів)')
                    ->hideFromIndex(),
                Textarea::make('Meta Description','meta_description')
                    ->rows(3)
                    ->help('Опис для SEO (до 160 символів)'),
                Text::make('Meta Keywords','meta_keywords')
                    ->help('Ключові слова через кому')
                    ->hideFromIndex(),
                Text::make('H1','h1')
                    ->help('Заголовок H1 на сторінці категорії')
                    ->hideFromIndex(),
            ])->setTitle('SEO налаштування'),

            NovaTabTranslatable::make([
                Trix::make('Контент','content')
                    ->help('Основний контент категорії')
                    ->hideFromIndex(),
                Trix::make('SEO текст','seo_text')
                    ->help('Додатковий SEO текст внизу сторінки')
                    ->hideFromIndex(),
            ])->setTitle('Контент'),

            BelongsTo::make('Основна Категорія', 'parent', self::class)
                ->nullable()
                ->displayUsing(function ($parent) {
                    return $parent->getNameUkAttribute();
                }),

            HasMany::make('Під Категорія', 'children', self::class),
            HasMany::make('Продукти', 'products', Product::class),

            Images::make('Фото','main')
                ->withMeta(['style' => 'border: 1px solid #ddd; background: #f5f5f5; padding: 5px;']),
        ];
    }
}
