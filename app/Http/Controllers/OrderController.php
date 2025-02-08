<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Notifications\TemporaryPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone'            => 'required|string',
            'first-name'       => 'required|string',
            'last-name'        => 'required|string',
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'shipping_method'  => 'required|string',
            'payment_method'   => 'required|string',
            'comment'          => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'city'             => 'nullable|string',
        ]);

        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())->get();
        } else {
            $cartItems = Session::get('cart', []);
        }

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            Session::forget('cart');
        }

        if (!Auth::check()) {
            $password = Str::random(8);

            $user = User::create([
                'name'      => $validated['first-name'],
                'last_name' => $validated['last-name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'password'  => Hash::make($password),
            ]);

            Auth::login($user);

            $user->notify(new TemporaryPasswordNotification($user->email, $password));
        }

        $order = new Order();
        $order->phone = $validated['phone'];
        $order->first_name = $validated['first-name'];
        $order->last_name = $validated['last-name'];
        $order->email = $validated['email'];
        $order->shipping_method = $validated['shipping_method'];
        $order->payment_method = $validated['payment_method'];
        $order->comment = $validated['comment'];
        $order->shipping_address = $validated['shipping_address'];
        $order->city = $validated['city'];
        $order->status = 'pending';
        $order->user_id = Auth::check() ? Auth::id() : null;
        $order->save();

        foreach ($cartItems as $cartItem) {
            $product = is_object($cartItem) ? $cartItem->product : $cartItem['product'];
            $quantity = is_object($cartItem) ? $cartItem->quantity : $cartItem['quantity'];

            OrderProduct::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->getPrice(),
            ]);
        }



        return redirect()->route('checkout.success');
    }
}
