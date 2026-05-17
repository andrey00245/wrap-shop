<?php

namespace App\Nova\Support;

use App\Support\LocaleFaqItems;
use Laravel\Nova\Fields\Code;

final class LocaleFaqNovaFields
{
    /**
     * @return list<\Laravel\Nova\Fields\Code>
     */
    public static function make(): array
    {
        $fields = [];

        foreach (LocaleFaqItems::LOCALES as $locale) {
            $label = $locale === 'uk' ? 'укр' : 'рус';
            $attribute = 'faq_schema_'.$locale;

            $fields[] = Code::make("FAQ для schema.org ({$label})", $attribute)
                ->json()
                ->nullable()
                ->hideFromIndex()
                ->resolveUsing(function ($value, $resource) use ($locale) {
                    $items = LocaleFaqItems::forLocale($resource->faq_items ?? null, $locale);

                    return json_encode($items, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?: '[]';
                })
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) use ($locale) {
                    if (! $request->exists($requestAttribute)) {
                        return;
                    }

                    $raw = $request->input($requestAttribute);
                    $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
                    $items = is_array($decoded) ? $decoded : [];

                    $model->faq_items = LocaleFaqItems::withLocale($model->faq_items, $locale, $items);
                })
                ->help('Приклад: [{"question":"Питання?","answer":"Відповідь HTML або текст"}]');
        }

        return $fields;
    }
}
