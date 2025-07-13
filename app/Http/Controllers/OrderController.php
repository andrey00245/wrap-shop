<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Notifications\TemporaryPasswordNotification;
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
        ];

        if (in_array($request->input('shipping_method'), ['flat', 'novaposhta', 'novaposhta_doors','my_addresses'])) {
            $rules['city'] = 'required|string';
            $rules['shipping_address'] = 'required|string';
        }

        if ($request->input('shipping_method') == 'my_addresses') {
            $rules['city_select'] = 'required|string';
            $rules['shipping_address'] = 'required|string';
        }

        $validated = $request->validate($rules);  // Применение валидации

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
            $user->notify(new TemporaryPasswordNotification($user->email, $password));
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
        $order->shipping_method = Arr::get($validated,'shipping_method','-');
        $order->payment_method = Arr::get($validated,'payment_method');
        $order->comment = Arr::get($validated,'comment');
        $order->shipping_address = Arr::get($validated,'shipping_address');
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
        }

        if ($order->payment_method === 'online') {
            $wfpService = new \App\Services\WayForPayService();
            $formData = $wfpService->generatePaymentData($order);
            $order->update(['payment_status' => 'pending']);

            return view('base.pages.checkout.wayforpay.form', compact('formData'));
        }

        \App\Services\MoySkladSyncService::sendOrder($order);

        return redirect()->route('checkout.success');

//
//        if (Auth::check()) {
//            CartItem::where('user_id', Auth::id())->delete();
//        } else {
//            Session::forget('cart');
//        }

        return redirect()->route('checkout.success');
    }
}
