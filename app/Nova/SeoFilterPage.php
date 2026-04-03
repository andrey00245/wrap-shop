<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;

class SeoFilterPage extends Resource
{
    public static $model = \App\Models\SeoFilterPage::class;

    public static $title = 'slug';

    public static function label(): string
    {
        return 'SEO-сторінки фільтрів';
    }

    public static $search = ['slug', 'id', 'filter_value'];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with(['category', 'attribute']);
    }

    public function fields(NovaRequest $request): array
    {
        return [
            BelongsTo::make('Категорія', 'category', Category::class)
                ->rules('required')
                ->showCreateRelationButton()
                ->displayUsing(fn ($c) => $c ? $c->getNameUkAttribute() : null),

            BelongsTo::make('Атрибут фільтра', 'attribute', Attribute::class)
                ->rules('required')
                ->help('Атрибут, за яким будуть фільтруватись товари (наприклад: Колір, Бренд)')
                ->displayUsing(fn ($a) => $a ? $a->getTranslation('name', 'uk') : null),

            Text::make('Значення фільтра', 'filter_value')
                ->rules('required', 'string', 'max:255')
                ->help('Значення як у каталозі (наприклад: Чорний, 3M). Регістр і пробіли не мають значення — «Чорний» і «чорний» дадуть однаковий результат.'),

            Text::make('ЧПУ (slug)', 'slug')
                ->rules('required', 'string', 'max:255')
                ->help('Наприклад: kolir-chornyj, brand-3m. Можна залишити порожнім — згенерується з атрибута та значення.'),

            NovaTabTranslatable::make([
                Text::make('Meta Title', 'meta_title')
                    ->help('Заголовок для SEO')
                    ->hideFromIndex(),
                Textarea::make('Meta Description', 'meta_description')
                    ->rows(3)
                    ->hideFromIndex(),
            ])->setTitle('SEO'),

            NovaTabTranslatable::make([
                CkEditor::make('SEO текст', 'seo_text')
                    ->help('Текст на сторінці (як у категоріях)')
                    ->hideFromIndex(),
            ])->setTitle('Контент'),

            Boolean::make('Активна', 'is_active')->default(true),

            \Laravel\Nova\Fields\Text::make('Посилання', 'link')
                ->displayUsing(function ($value) {
                    if (! $value || $value === '—') {
                        return '—';
                    }

                    return '<a href="'.e($value).'" target="_blank" rel="noopener" class="link-default">'.e($value).'</a>';
                })
                ->asHtml()
                ->onlyOnDetail()
                ->help('Як виглядатиме URL сторінки на сайті. Клік відкриє сторінку в новій вкладці.'),
        ];
    }
}
