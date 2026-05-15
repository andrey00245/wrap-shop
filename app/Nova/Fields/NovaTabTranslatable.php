<?php

namespace App\Nova\Fields;

use Illuminate\Database\Eloquent\Model;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable as Base;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * Виправлення сумісності з Laravel Nova 4: у батька is_callable() інкоректно
 * спрацьовує для масивів правил, після чого call_user_func падає з
 * "array callback must have exactly two members".
 *
 * Spatie HasTranslations: батьківський resolve читає $model->translations['title']['uk'],
 * але для нового запису title може бути [] — звернення до ключа локалі ламає API creation-fields.
 */
class NovaTabTranslatable extends Base
{
    protected function createTranslatedField(Field $originalField, string $locale): Field
    {
        $translatedField = parent::createTranslatedField($originalField, $locale);

        $originalAttribute = $originalField->attribute;

        $translatedField->resolveUsing(function ($value, Model $model) use ($locale, $originalAttribute) {
            if (method_exists($model, 'getTranslation')) {
                return (string) ($model->getTranslation($originalAttribute, $locale, false) ?? '');
            }

            $translations = $model->translations ?? [];

            if (! isset($translations[$originalAttribute]) || ! is_array($translations[$originalAttribute])) {
                return '';
            }

            return (string) ($translations[$originalAttribute][$locale] ?? '');
        });

        return $translatedField;
    }

    protected function getSituationalRulesSet(NovaRequest $request, string $propertyName = 'rules')
    {
        $fieldsRules = [$this->attribute => []];

        foreach ($this->data as $field) {
            $value = $field->{$propertyName};

            if ($value instanceof \Closure) {
                $fieldsRules[$field->attribute] = $value($request);
            } elseif (is_callable($value)) {
                $fieldsRules[$field->attribute] = call_user_func($value, $request);
            } elseif (is_array($value)) {
                $fieldsRules[$field->attribute] = $value;
            } else {
                $fieldsRules[$field->attribute] = $value;
            }
        }

        return $fieldsRules;
    }
}
