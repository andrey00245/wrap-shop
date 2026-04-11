<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Notifications\TemporaryPasswordNotification;
use App\Services\CheckboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        $rules = [
            'phone' => 'required|string',
            'first-name' => 'required|string',
            'last-name' => 'required|string',
            'email' => Auth::check() ? 'nullable' : 'required|string|email|max:255|unique:' . User::class,
            'payment_method' => 'required|in:cash,online,bank_transfer',
            'comment' => 'nullable|string',
            'shipping_method' => 'string',
            'novaposhta_warehouse_ref' => 'nullable|string',
            'nova_poshta_type' => 'nullable|string|in:branch,locker,courier',
            'locker_address' => 'nullable|string',
            'courier_street' => 'nullable|string',
            'courier_house' => 'nullable|string',
            'kyiv_address' => 'nullable|string',
        ];

        if (in_array($request->input('shipping_method'), ['flat', 'novaposhta', 'novaposhta_doors','my_addresses'])) {
            $rules['city'] = 'required|string';

            // Для Nova Poshta проверяем тип доставки
            if ($request->input('shipping_method') === 'novaposhta') {
                $novaPoshtaType = $request->input('nova_poshta_type');
                if ($novaPoshtaType === 'branch') {
                    $rules['shipping_address'] = 'required|string';
                } elseif ($novaPoshtaType === 'locker') {
                    $rules['locker_address'] = 'required|string';
                } elseif ($novaPoshtaType === 'courier') {
                    $rules['courier_street'] = 'required|string';
                    $rules['courier_house'] = 'required|string';
                }
            } elseif ($request->input('shipping_method') === 'flat') {
                $rules['kyiv_address'] = 'required|string';
            } else {
                $rules['shipping_address'] = 'required|string';
            }
        }

        if ($request->input('shipping_method') == 'my_addresses') {
            $rules['city_select'] = 'required|string';
            $rules['my_address'] = 'required|string';
        }

        $validated = $request->validate($rules);


        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())->get();
        } else {
            $cartItems = Session::get('cart', []);
        }

        // Проверка для неавторизованных пользователей
        if (!Auth::check()) {
            $password = Str::random(8);

            $user = User::create([
                'name' => $validated['first-name'],
                'last_name' => $validated['last-name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($password),
            ]);

            Auth::login($user);

            // Тимчасово вимкнено SMTP-надсилання пароля, щоб не блокувати checkout таймаутами.
            \Log::info('Temporary password email skipped (SMTP disabled temporarily).', [
                'user_id' => $user->id,
            ]);
        }

        $totalSum = 0;
        foreach ($cartItems as $cartItem) {
            $product = is_object($cartItem) ? $cartItem->product : $cartItem['product'];
            $quantity = is_object($cartItem) ? $cartItem->quantity : $cartItem['quantity'];
            $totalSum += $product->getPriceByCount($quantity);
        }

        $order = new Order();
        $order->phone = Arr::get($validated,'phone');
        $order->first_name = Arr::get($validated,'first-name');
        $order->last_name = Arr::get($validated,'last-name');
        $order->email = Auth::check() ? Auth::user()->email : Arr::get($validated,'email');
        $order->shipping_method = Arr::get($validated,'shipping_method');
        $order->payment_method = Arr::get($validated,'payment_method');
        $order->comment = Arr::get($validated,'comment');
        // Определяем адрес доставки в зависимости от типа
        if ($request->input('shipping_method') === 'novaposhta') {
            $novaPoshtaType = $request->input('nova_poshta_type');

            \Log::info('Nova Poshta заказ', [
                'shipping_method' => $request->input('shipping_method'),
                'nova_poshta_type' => $novaPoshtaType,
                'all_request_data' => $request->all()
            ]);
            if ($novaPoshtaType === 'branch') {
                $order->shipping_address = Arr::get($validated,'shipping_address');
                $order->novaposhta_warehouse_ref = $request->input('novaposhta_warehouse_ref');
            } elseif ($novaPoshtaType === 'locker') {
                $order->shipping_address = Arr::get($validated,'locker_address');
                // Для почтоматов нужно сохранить ID почтомата
                $order->novaposhta_warehouse_ref = $request->input('locker_warehouse_ref');

                \Log::info('Сохранение почтомата', [
                    'nova_poshta_type' => $novaPoshtaType,
                    'locker_address' => Arr::get($validated,'locker_address'),
                    'locker_warehouse_ref' => $request->input('locker_warehouse_ref'),
                    'novaposhta_warehouse_ref' => $order->novaposhta_warehouse_ref
                ]);
            } elseif ($novaPoshtaType === 'courier') {
                $order->shipping_address = 'Кур\'єром: ' . Arr::get($validated,'courier_street') . ', ' . Arr::get($validated,'courier_house');
                $order->novaposhta_warehouse_ref = null; // Для курьера нет warehouse_ref
            }
        } elseif ($request->input('shipping_method') === 'flat') {
            $order->shipping_address = Arr::get($validated,'kyiv_address');
        } elseif ($request->input('shipping_method') === 'my_addresses') {
            $order->shipping_address = Arr::get($validated,'my_address');
        } else {
            $order->shipping_address = Arr::get($validated,'shipping_address');
        }
        $order->city = Arr::get($validated,'city');
        $order->status = 'pending';
        $order->user_id = Auth::check() ? Auth::id() : null;
        $order->total = $totalSum;

        $order->save();

        foreach ($cartItems as $cartItem) {
            $product = is_object($cartItem) ? $cartItem->product : $cartItem['product'];
            $quantity = is_object($cartItem) ? $cartItem->quantity : $cartItem['quantity'];
            $productPrice = $product->getPrice();
            if ($product->getRollSize()) {
                if ($quantity >= 10 && $quantity <= 24) {
                    $productPrice = $product->getSmallPrice();
                }
                if ($quantity >= 25) {
                    $productPrice = $product->getBigPrice();
                }
            }

            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $productPrice,
            ]);

            // Обновление остатка товара
            $currentStock = (float) $product->getStock();
            $newStock = $currentStock - (float) $quantity;
            if ($newStock < 0) {
                $newStock = 0.0;
            }
            $product->stock = $newStock;
            $product->save();
        }

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            Session::forget('cart');
        }

        if ($order->payment_method === 'online') {
            \App\Services\MoySkladSyncService::sendOrder($order);

            // Обновляем данные контрагента при изменении типа доставки
            \App\Services\MoySkladSyncService::updateCounterpartyDelivery($order);

            $wfpService = new \App\Services\WayForPayService();
            $formData = $wfpService->generatePaymentData($order);
            $order->update(['payment_status' => 'pending']);

            return view('base.pages.checkout.wayforpay.form', compact('formData'));
        }

        \App\Services\MoySkladSyncService::sendOrder($order);

        // Обновляем данные контрагента при изменении типа доставки
        \App\Services\MoySkladSyncService::updateCounterpartyDelivery($order);

        return redirect()->route('checkout.success');
    }
}
