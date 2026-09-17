<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TranslatedStatusFilter extends Filter
{
    public $name = 'Статус перекладу';

    public function apply(Request $request, $query, $value)
    {
        // Приблизний SQL: порожні локалі або en = uk (копіпаст кирилиці в en).
        // Точна перевірка — в Product::is_translated (колонка в індексі).
        if ($value === 'translated') {
            return $query->whereJsonLength('name->ru', '>', 0)
                ->whereJsonLength('name->en', '>', 0)
                ->whereJsonLength('descriptions->ru', '>', 0)
                ->whereJsonLength('descriptions->en', '>', 0)
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) != JSON_UNQUOTE(JSON_EXTRACT(name, '$.uk'))")
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(descriptions, '$.en')) != JSON_UNQUOTE(JSON_EXTRACT(descriptions, '$.uk'))");
        }

        if ($value === 'not_translated') {
            return $query->where(function ($q) {
                $q->whereNull('name->ru')
                    ->orWhereNull('name->en')
                    ->orWhereNull('descriptions->ru')
                    ->orWhereNull('descriptions->en')
                    ->orWhereRaw("TRIM(COALESCE(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ru')), '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')), '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(JSON_UNQUOTE(JSON_EXTRACT(descriptions, '$.ru')), '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(JSON_UNQUOTE(JSON_EXTRACT(descriptions, '$.en')), '')) = ''")
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) = JSON_UNQUOTE(JSON_EXTRACT(name, '$.uk'))")
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(descriptions, '$.en')) = JSON_UNQUOTE(JSON_EXTRACT(descriptions, '$.uk'))");
            });
        }

        return $query;
    }

    public function options(Request $request)
    {
        return [
            'Перекладено' => 'translated',
            'Не перекладено' => 'not_translated',
        ];
    }
}
