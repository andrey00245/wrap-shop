<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class WayForPayController extends Controller
{
    public function success()
    {
        $theme = Session::get('theme') ?? 'dark';
        Session::put('theme', $theme);

        return redirect()->route('checkout.success');
    }

    public function callback(Request $request)
    {
        $data = $request->all();

        Log::info('WayForPay callback получен', [
            'url'    => $request->fullUrl(),
            'method' => $request->method(),
            'data'   => $data
        ]);

        $order = Order::where('moysklad_id', $data['orderReference'])->first();

        if ($order) {
            $status = strtolower($data['transactionStatus']);
            $order->update(['payment_status' => $status]);

            if ($status === 'approved') {
                try {
                    \App\Services\MoySkladSyncService::updateOrderPaymentStatus($order);
                } catch (\Exception $e) {
                    Log::error('Ошибка обновления заказа в МойСклад', [
                        'order_id' => $order->id,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }
        } else {
            Log::warning("Заказ с moysklad_id {$data['orderReference']} не найден");
        }

        return response()->json([
            'orderReference' => $data['orderReference'],
            'status' => 'accept',
        ], 200, ['Content-Type' => 'application/json']);
    }
}
