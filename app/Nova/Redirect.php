<?php

namespace App\Nova;

use App\Models\Redirect as RedirectModel;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Redirect extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Redirect>
     */
    public static $model = RedirectModel::class;

    public static $title = 'from_url';

    public static $search = [
        'id',
        'from_url',
        'to_url',
    ];

    public static function label()
    {
        return 'Редиректи';
    }

    public static function singularLabel()
    {
        return 'Редирект';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Звідки (from_url)', 'from_url')
                ->rules('required', 'string', 'max:2048')
                ->help('Відносний шлях без домена, без UTM. Зберігайте як на старому сайті (часто без /catalog/), напр. /ru/plenki-1/... — якщо хтось зайде з /ru/catalog/plenki-1/..., редирект теж знайдеться автоматично.'),

            Text::make('Куди (to_url)', 'to_url')
                ->rules('required', 'string', 'max:2048')
                ->help('Може бути відносним шляхом (/novyj-url) або повним URL (https://site.com/novyj-url)'),

            Number::make('Код', 'status_code')
                ->min(300)
                ->max(399)
                ->step(1)
                ->default(301)
                ->help('HTTP‑код редиректа. За замовчуванням 301.'),

            Boolean::make('Активний', 'is_active')
                ->sortable(),
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
        return [
            new \App\Nova\Actions\ImportRedirects,
        ];
    }
}
