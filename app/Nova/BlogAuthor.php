<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use App\Nova\Fields\Images;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class BlogAuthor extends Resource
{
    public static $model = \App\Models\BlogAuthor::class;

    public static $title = 'slug';

    public static $search = [
        'id', 'slug',
    ];

    public static function label()
    {
        return 'Блог — автори';
    }

    public function title()
    {
        $m = $this->resource;
        if (! $m instanceof \App\Models\BlogAuthor) {
            return '—';
        }

        return $m->getTranslation('name', 'uk') ?: $m->slug;
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('URL (slug)', 'slug')
                ->readonly()
                ->hideWhenCreating()
                ->help('Формується автоматично з імені (пріоритет мова: uk → en → ru).'),
            NovaTabTranslatable::make([
                Text::make('Ім’я', 'name')->rules('required'),
                Text::make('Роль', 'role'),
                Textarea::make('Біо', 'bio')->rows(4),
            ]),
            Images::make('Аватар', 'avatar')
                ->singleMediaRules('image')
                ->help('Якщо не завантажено — на сайті показується логотип сайту (як зараз).'),
            KeyValue::make('Соцмережі (ключ → URL)', 'socials')
                ->keyLabel('Ключ')
                ->valueLabel('URL')
                ->nullable()
                ->help(
                    'Допустимі ключі (латиниця, нижній регістр): instagram, telegram, facebook, youtube, twitter, x, linkedin, tiktok, website.'
                ),
            Boolean::make('Активний', 'is_active')->default(true),
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
