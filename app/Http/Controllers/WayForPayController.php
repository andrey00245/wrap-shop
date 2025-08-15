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

        Log::error('WayFor pay response', [
            'response' => $data
        ]);

        if (($data['transactionStatus'] ?? null) === 'Approved') {
            $order = Order::where('id', explode('_', $data['orderReference'])[0])->first();

            if ($order) {
                // Обновляем статус оплаты в локальной БД
                $order->update(['payment_status' => 'approved']);

                // Обновляем статус заказа в МойСклад
                try {
                    \App\Services\MoySkladSyncService::updateOrderPaymentStatus($order);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Ошибка обновления заказа в МойСклад', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return response('OK');
    }
}
