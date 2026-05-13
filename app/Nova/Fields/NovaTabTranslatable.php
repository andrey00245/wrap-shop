<?php

namespace App\Nova\Fields;

use Kongulov\NovaTabTranslatable\NovaTabTranslatable as Base;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * Виправлення сумісності з Laravel Nova 4: у батька is_callable() інкоректно
 * спрацьовує для масивів правил, після чого call_user_func падає з
 * "array callback must have exactly two members".
 */
class NovaTabTranslatable extends Base
{
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
