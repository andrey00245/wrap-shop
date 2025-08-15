<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class NewUsers extends Value
{
    public function name()
    {
        return 'Нові користувачі';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->count($request, User::class);
    }

    public function ranges()
    {
        return [
            7 => '7 днів',
            30 => '30 днів',
            60 => '60 днів',
            365 => '365 днів',
            'MTD' => 'Місяць (поточний)',
            'QTD' => 'Квартал (поточний)',
            'YTD' => 'Рік (поточний)'
        ];
    }

    public function uriKey()
    {
        return 'new-users';
    }
}