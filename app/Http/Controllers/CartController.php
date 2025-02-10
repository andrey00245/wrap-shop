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
            $user = Auth::user();

            // Поиск товара в корзине
            $cartItem = $user->cartItems()->where('product_id', $productId)->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Если товара нет в корзине, добавляем новый
                CartItem::create([
                    'product_id' => $productId,
                    'user_id' => $user->id,
                    'quantity' => $quantity,
                ]);
            }

            // Пересчитываем общую сумму корзины
            foreach ($user->cartItems as $item) {
                $sum += $item->product->getPriceByCount($item->quantity);
            }

            // Округление суммы до 2 знаков после запятой
            $sum = round($sum, 2);

            // Генерация HTML для корзины
            $html = view('base.components.cart-items', [
                'cartItems' => $user->cartItems,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => $sum,
            ])->render();

            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => $sum,
            ]);
        }

        // Для неавторизованных пользователей
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

        // Сохраняем изменения в сессии
        Session::put('cart', $cartItems);
        $cartItemsCount = count($cartItems);

        // Пересчитываем общую сумму корзины
        foreach ($cartItems as $item) {
            $sum += $item['product']->getPriceByCount($item['quantity']);
        }

        // Округление суммы до 2 знаков после запятой
        $sum = round($sum, 2);

        // Генерация HTML для корзины
        $html = view('base.components.cart-items', compact([
            'cartItems',
            'cartItemsCount',
            'sum',
        ]))->render();

        return response()->json([
            'cartItems' => $html,
            'cartItemsCount' => $cartItemsCount,
            'sum' => $sum,
        ]);
    }

    public function remove(Request $request, $productId)
    {
        $sum = 0;
        if (Auth::check()) {
            $user = Auth::user();

            // Удаление товара из корзины
            CartItem::where('product_id', $productId)
                ->where('user_id', $user->id)
                ->delete();

            // Пересчитываем общую сумму корзины
            foreach ($user->cartItems as $item) {
                $sum += $item->product->getPriceByCount($item->quantity);
            }

            // Округляем сумму
            $sum = round($sum, 2);

            // Генерация HTML для корзины
            $html = view('base.components.cart-items', [
                'cartItems' => $user->cartItems,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => $sum,
            ])->render();

            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => $sum,
            ]);
        }

        // Для неавторизованных пользователей
        $cartItems = Session::get('cart', []);

        if (isset($cartItems[$productId])) {
            unset($cartItems[$productId]);
            Session::put('cart', $cartItems);
            $cartItemsCount = count($cartItems);

            // Пересчитываем общую сумму корзины
            foreach ($cartItems as $item) {
                $sum += $item['product']->getPriceByCount($item['quantity']);
            }

            // Округляем сумму
            $sum = round($sum, 2);

            // Генерация HTML для корзины
            $html = view('base.components.cart-items', compact([
                'cartItems',
                'cartItemsCount',
                'sum',
            ]))->render();

            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $cartItemsCount,
                'sum' => $sum,
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
                $sum += $item->product->getPriceByCount($item->quantity);
            }

            // Округляем сумму до 2 знаков после запятой
            $sum = round($sum, 2);

            // Генерация HTML для корзины
            $html = view('base.components.cart-items', [
                'cartItems' => $user->cartItems,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => $sum,
            ])->render();

            // Округляем цену обновленного товара
            $updatedPrice = round($cartItem->product->getPriceByCount($quantity), 2);

            // Возвращаем обновленные данные
            return response()->json([
                'cartItems' => $html,
                'cartItemsCount' => $user->cartItems->count(),
                'sum' => $sum,
                'updatedPrice' => $updatedPrice,
                'cartTotal' => $sum,
            ]);
        }

        // Для неавторизованных пользователей
        $cartItems = Session::get('cart', []);

        if (isset($cartItems[$productId])) {
            // Обновляем количество товара
            $cartItems[$productId]['quantity'] = $quantity;
        } else {
            // Если товара нет в корзине, добавляем его
            $product = Product::find($productId);
            if ($product) {
                $cartItems[$productId] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }

        // Сохраняем изменения в сессии
        Session::put('cart', $cartItems);

        // Пересчитываем общую сумму корзины
        foreach ($cartItems as $item) {
            $sum += $item['product']->getPriceByCount($item['quantity']);
        }

        // Округляем сумму до 2 знаков после запятой
        $sum = round($sum, 2);

        // Генерация HTML для корзины
        $html = view('base.components.cart-items', [
            'cartItems' => $cartItems,
            'cartItemsCount' => count($cartItems),
            'sum' => $sum,
        ])->render();

        // Округляем цену обновленного товара
        $updatedPrice = round($cartItems[$productId]['product']->getPriceByCount($quantity), 2);

        return response()->json([
            'cartItems' => $html,
            'cartItemsCount' => count($cartItems),
            'sum' => $sum,
            'updatedPrice' => $updatedPrice,
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
