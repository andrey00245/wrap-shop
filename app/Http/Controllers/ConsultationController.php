<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'comment' => 'nullable|string',
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $consultation = Consultation::create($validated);

        // Возвращаем успешный ответ
        return response()->json([
            'status' => 'success',
            'message' => __('popup.consult_popup.thanks_message')
        ]);
    }
}
