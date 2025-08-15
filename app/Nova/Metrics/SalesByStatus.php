<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Http\Requests\NovaRequest;

class SalesByStatus extends Partition
{
    public function name()
    {
        return 'Замовлення за статусом';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order::class, 'status');
    }

    public function uriKey()
    {
        return 'sales-by-status';
    }
}