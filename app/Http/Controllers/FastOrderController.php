<?php

namespace App\Http\Controllers;

use App\Models\FastOrder;
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

        $fastOrder = FastOrder::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'comment' => $validated['comment'],
            'total_price' => $validated['total_price'],
            'user_id' => Auth::id(),
            'status' => 'new',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ваш заказ принят. Спасибо!',
            'fast_order' => $fastOrder,
        ]);
    }
}
