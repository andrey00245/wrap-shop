<?php

namespace App\Http\Controllers;

use App\Models\ReportAvailability;
use Illuminate\Http\Request;

class ReportAvailabilityController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'product_id' => 'required|integer|exists:products,id',
        ]);

         ReportAvailability::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Ваш заказ принят. Спасибо!',
        ]);
    }
}
