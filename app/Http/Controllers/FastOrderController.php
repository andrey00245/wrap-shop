<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FastOrderController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email',
            'comment' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Получаем продукт
        $product = Product::findOrFail($validated['product_id']);

        // Разделяем имя на имя и фамилию
        $nameParts = explode(' ', trim($validated['name']), 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // Создаем обычный заказ
        $order = Order::create([
            'user_id' => Auth::id(),
            'phone' => $validated['phone'],
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $validated['email'] ?? '',
            'shipping_method' => '', // Быстрый заказ - пустая доставка
            'comment' => $validated['comment'] ?? '',
            'status' => 'new',
            'is_fast_order' => true, // Флаг быстрого заказа
            'total' => $validated['total_price'],
        ]);

        // Добавляем продукт к заказу
        $order->products()->attach($product->id, [
            'quantity' => $validated['quantity'],
            'price' => $validated['total_price'] / $validated['quantity'], // Цена за единицу
        ]);

        // Отправляем в МойСклад
        \App\Services\MoySkladSyncService::sendOrder($order);

        return response()->json([
            'status' => 'success',
            'message' => 'Ваш заказ принят. Спасибо!',
            'order' => $order,
        ]);
    }
}
