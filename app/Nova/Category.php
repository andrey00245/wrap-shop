<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;
use Illuminate\Validation\ValidationException;

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
        'id',
    ];

    /**
     * @return array<int, string>
     */
    public static function searchableColumns()
    {
        $locales = config('tab-translatable.locales', ['uk', 'ru', 'en']);
        $columns = ['id'];
        foreach ($locales as $locale) {
            $columns[] = "name->{$locale}";
            $columns[] = "slug->{$locale}";
        }

        return $columns;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            NovaTabTranslatable::make([
                Text::make('Назва', 'name'),
                Text::make('Slug', 'slug'),
            ])->setTitle('Основна інформація'),

            NovaTabTranslatable::make([
                Text::make('Meta Title', 'meta_title')
                    ->help('Заголовок для SEO (до 60 символів)')
                    ->hideFromIndex(),
                Textarea::make('Meta Description', 'meta_description')
                    ->rows(3)
                    ->help('Опис для SEO (до 160 символів)'),
                Text::make('Meta Keywords', 'meta_keywords')
                    ->help('Ключові слова через кому')
                    ->hideFromIndex(),
                Text::make('H1', 'h1')
                    ->help('Заголовок H1 на сторінці категорії')
                    ->hideFromIndex(),
            ])->setTitle('SEO налаштування'),

            NovaTabTranslatable::make([
                CkEditor::make('Контент', 'content')
                    ->help('Основний контент категорії')
                    ->hideFromIndex(),
                CkEditor::make('SEO текст', 'seo_text')
                    ->help('Додатковий SEO текст внизу сторінки')
                    ->hideFromIndex(),
            ])->setTitle('Контент'),

            Code::make('FAQ для schema.org (JSON)', 'faq_items')
                ->json()
                ->nullable()
                ->hideFromIndex()
                ->resolveUsing(function ($value) {
                    if ($value === null || $value === '') {
                        return null;
                    }
                    if (is_string($value)) {
                        return $value;
                    }

                    return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                })
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $raw = $request[$requestAttribute] ?? null;
                    if ($raw === null) {
                        $model->{$attribute} = null;

                        return;
                    }
                    $raw = trim((string) $raw);
                    if ($raw === '' || strtolower($raw) === 'null') {
                        $model->{$attribute} = null;

                        return;
                    }

                    $decoded = json_decode($raw, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw ValidationException::withMessages([
                            $requestAttribute => 'Некоректний JSON (перевірте коми/лапки).',
                        ]);
                    }

                    $model->{$attribute} = $decoded;
                })
                ->rules('nullable', 'json')
                ->help('Приклад: [{"question":"Питання?","answer":"Відповідь HTML або текст"}]'),

            BelongsTo::make('Основна Категорія', 'parent', self::class)
                ->nullable()
                ->displayUsing(function ($parent) {
                    return $parent->getNameUkAttribute();
                }),

            HasMany::make('Під Категорія', 'children', self::class),
            HasMany::make('Продукти', 'products', Product::class),
            BelongsToMany::make('FAQ', 'faqs', Faq::class)
                ->searchable()
                ->fields(function () {
                    return [
                        Text::make('Порядок', 'sort_order')
                            ->rules('nullable', 'integer', 'min:0')
                            ->help('Сортування FAQ всередині категорії'),
                    ];
                }),

            Images::make('Фото', 'main')
                ->withMeta(['style' => 'border: 1px solid #ddd; background: #f5f5f5; padding: 5px;']),
        ];
    }
}
