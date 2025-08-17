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
        $data = $request->json()?->all();

        Log::info('WayForPay callback получен', [
            'url'    => $request->fullUrl(),
            'method' => $request->method(),
            'data'   => $data
        ]);

        if (!isset($data['orderReference'])) {
            Log::error('orderReference отсутствует в колбеке', ['data' => $data]);
            return response()->json(['status' => 'error'], 400);
        }

        list($orderId, $timestamp) = explode('_', $data['orderReference']);
        $order = Order::where('id', $orderId)->first();

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
