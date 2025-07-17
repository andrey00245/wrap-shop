<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
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

        if (($data['transactionStatus'] ?? null) === 'Approved') {
            $order = Order::where('id', explode('_', $data['orderReference'])[0])->first();
            $order->update(['payment_status' => 'approved']);

//            if ($order) {
//                $checkboxService = new \App\Services\CheckboxService();
//                $checkboxService->sendReceipt($order);
//            }
        }

        return response('OK');
    }
}
