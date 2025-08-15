<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Http\Requests\NovaRequest;

class OrdersPerDay extends Trend
{
    public function name()
    {
        return 'Замовлення за день';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Order::class);
    }

    public function ranges()
    {
        return [
            7 => '7 днів',
            30 => '30 днів',
            60 => '60 днів',
            365 => '365 днів',
        ];
    }

    public function uriKey()
    {
        return 'orders-per-day';
    }
}