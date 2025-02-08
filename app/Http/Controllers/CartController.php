<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $sum = 0;

        if (Auth::check()) {
            /**
             * @var User $user;
             */
            $user = Auth::user();

            $cartItem = $user->cartItems()->where('product_id', $productId)->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'product_id' => $productId,
                    'user_id' => $user->id,
                    'quantity' => $quantity,
                ]);
            }

            foreach ($user->cartItems  as $item)
            {
                $sum += $item->getPriceByCount($item->quantity);
            }

            $html = view('base.components.cart-items',
                [
                    'cartItems' => $user->cartItems,
                    'cartItemsCount' => $user->cartItems->count(),
                    'sum' => $sum,
                ]
            )->render();

            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $user->cartItems->count()
            ]);
        }

        $cartItems = Session::get('cart', []);

        if (isset($cartItems[$productId])) {
            $cartItems[$productId]['quantity'] += $quantity;
        } else {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[$productId] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }


        Session::put('cart', $cartItems);
        $cartItemsCount = count($cartItems);

        foreach ($cartItems  as $item)
        {
            $sum += $item['product']->getPriceByCount($item['quantity']);
        }

        $html = view('base.components.cart-items', compact([
            'cartItems',
            'cartItemsCount',
            'sum'
        ]))->render();
        return response()->json([
            'cartItems' => $html,
            'cartItemsCount' => $cartItemsCount,
        ]);
    }

    public function remove(Request $request, $productId)
    {
        $sum = 0;
        if (Auth::check()) {
            $user = Auth::user();
           CartItem::where('product_id', $productId)
                ->where('user_id', $user->id)
                ->delete();

            foreach ($user->cartItems  as $item)
            {
                $sum += $item->getPriceByCount($item->quantity);
            }

            $html = view('base.components.cart-items',
                [
                    'cartItems' => $user->cartItems,
                    'cartItemsCount' => $user->cartItems->count(),
                    'sum',
                ]
            )->render();

            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $user->cartItems->count(),
            ]);
        }

        $cartItems = Session::get('cart', []);


        if (isset($cartItems[$productId])) {
            unset($cartItems[$productId]);
            Session::put('cart', $cartItems);
            $cartItemsCount = count($cartItems);
            foreach ($cartItems  as $item)
            {

                $sum += $item['product']->getPriceByCount($item['quantity']);
            }

            $html = view('base.components.cart-items', compact([
                'cartItems',
                'cartItemsCount',
                'sum'
            ]))->render();

            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $cartItemsCount,
            ]);
        }

        return response()->json(['message' => 'Товар не найден в корзине'], 404);
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $sum = 0;
        $updatedPrice = 0;

        if (Auth::check()) {
            $user = Auth::user();

            // Получаем товар в корзине пользователя
            $cartItem = $user->cartItems()->where('product_id', $productId)->first();

            if ($cartItem) {
                // Обновляем количество товара в корзине
                $cartItem->quantity = $quantity;
                $cartItem->save();
            } else {
                // Добавляем товар в корзину, если его нет
                CartItem::create([
                    'product_id' => $productId,
                    'user_id' => $user->id,
                    'quantity' => $quantity,
                ]);
            }

            // Пересчитываем общую сумму корзины
            foreach ($user->cartItems as $item) {
                $sum += $item->getPriceByCount($item->quantity);
            }

            // Генерируем HTML для обновления корзины
            $html = view('base.components.cart-items', [
                'cartItems' => $user->cartItems,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => round($sum, 2),
            ])->render();

            // Возвращаем обновленные данные корзины
            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => round($sum, 2),
                'updatedPrice' => $cartItem->product->getPriceByCount($quantity),
                'cartTotal' => $sum,
            ]);
        }

        // Если пользователь не авторизован, то работаем с сессионной корзиной
        $cartItems = Session::get('cart', []);

        if (isset($cartItems[$productId])) {
            // Если товар уже есть в корзине, обновляем количество
            $cartItems[$productId]['quantity'] = $quantity;
        } else {
            // Если товара нет в корзине, добавляем новый товар
            $product = Product::find($productId);
            if ($product) {
                $cartItems[$productId] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }

        // Сохраняем обновленные данные корзины в сессии
        Session::put('cart', $cartItems);

        // Пересчитываем общую сумму корзины
        foreach ($cartItems as $item) {
            $sum += $item['product']->getPriceByCount($item['quantity']);
        }

        // Генерируем HTML для обновления корзины
        $html = view('base.components.cart-items', [
            'cartItems' => $cartItems,
            'cartItemsCount' => count($cartItems),
            'sum' => $sum,
        ])->render();

        // Возвращаем обновленные данные
        return response()->json([
            'cartItems' => $html,
            'cartItemsCount' => count($cartItems),
            'sum' => $sum,
            'updatedPrice' => $cartItems[$productId]['product']->getPriceByCount($quantity),
            'cartTotal' => $sum,
        ]);
    }

    public function index()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cartItems = CartItem::with('product')
                ->where('user_id', $userId)
                ->get();

            return response()->json(['cartItems' => $cartItems]);
        } else {
            $cart = Session::get('cart', []);
            return response()->json(['cartItems' => $cart]);
        }
    }

    public function syncCart()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cart = Session::get('cart', []);

            foreach ($cart as $productId => $item) {
                CartItem::updateOrCreate(
                    ['product_id' => $productId, 'user_id' => $userId],
                    ['quantity' => $item['quantity']]
                );
            }

            Session::forget('cart');
            return response()->json(['message' => 'Корзина синхронизирована с базой данных']);
        }
        return response()->json(['message' => 'Пользователь не авторизован'], 401);
    }
}
