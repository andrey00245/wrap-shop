<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;

class BlogPost extends Resource
{
    public static $model = \App\Models\BlogPost::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'Блог — статті';
    }

    public function title()
    {
        $m = $this->resource;
        if (! $m instanceof \App\Models\BlogPost) {
            return '—';
        }

        return $m->getTranslation('title', 'uk') ?: ('#'.$m->id);
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Автор', 'author', BlogAuthor::class)->nullable(false),
            BelongsTo::make('Категорія', 'category', BlogCategory::class)->nullable(),
            BelongsTo::make('Каталог: категорія для блоку товарів', 'productCatalogCategory', Category::class)
                ->nullable()
                ->help('Якщо товари нижче не обрані — на сайті показуються до 10 активних товарів у наявності з цієї категорії та підкатегорій. Також використовується для лівого меню каталогу, якщо в обраних товарах немає категорії.'),
            BelongsToMany::make('Товари в блоці під статтею', 'attachedProducts', Product::class)
                ->searchable()
                ->fields(function () {
                    return [
                        Number::make('Порядок', 'sort_order')->default(0),
                    ];
                })
                ->help('Якщо додано хоча б один товар — у блоці показуються лише вони (у цьому порядку). Інакше — fallback за полем «Каталог: категорія».'),
            NovaTabTranslatable::make([
                Text::make('Заголовок (H1)', 'title')->rules('required'),
                Textarea::make('Анонс', 'excerpt')
                    ->rows(5)
                    ->stacked()
                    ->fullWidth()
                    ->hideFromIndex(),
                CkEditor::make('Текст статті', 'body')
                    ->stacked()
                    ->fullWidth()
                    ->help('Списки, таблиці — через панель редактора.')
                    ->hideFromIndex(),
                Text::make('Meta title', 'meta_title')->hideFromIndex(),
                Textarea::make('Meta description', 'meta_description')->rows(3)->hideFromIndex(),
            ]),
            Images::make('Обкладинка', 'cover')->singleMediaRules('image'),
            Number::make('Час читання (хв)', 'read_time_minutes')->nullable()->hideFromIndex(),
            DateTime::make('Опубліковано', 'published_at')
                ->nullable()
                ->help('Інформаційне поле (дата на сайті та в schema.org); видимість статті — лише від прапорця «Активна».')
                ->hideFromIndex(),
            Boolean::make('Активна', 'is_active')->default(true),
            Boolean::make('На головній блогу (hero)', 'is_featured')->default(false),
            Boolean::make('Коментарі увімкнено', 'comments_enabled')->default(true),
            Code::make('FAQ для schema.org (JSON)', 'faq_items')
                ->json()
                ->nullable()
                ->hideFromIndex()
                ->help('Приклад: [{"question":"Питання?","answer":"Відповідь HTML або текст"}]'),
            HasMany::make('Коментарі', 'comments', BlogComment::class),
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
