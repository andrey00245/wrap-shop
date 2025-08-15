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

        Log::info('WayForPay callback', $data);

        $orderId = explode('_', $data['orderReference'])[0];
        $order = Order::find($orderId);

        if ($order) {
            $order->update([
                'payment_status' => strtolower($data['transactionStatus'])
            ]);

            if ($data['transactionStatus'] === 'Approved') {
                try {
                    \App\Services\MoySkladSyncService::updateOrderPaymentStatus($order);
                } catch (\Exception $e) {
                    Log::error('Ошибка обновления заказа в МойСклад', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return response()->json([
            'orderReference' => $data['orderReference'],
            'status' => 'accept'
        ]);
    }
}
