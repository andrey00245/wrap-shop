<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class TotalSales extends Value
{
    public function name()
    {
        return 'Загальні продажі';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, Order::class, 'total');
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
        return 'total-sales';
    }
}