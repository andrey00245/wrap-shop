<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\MoySkladSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        // Отправляем консультацию в МойСклад
        try {
            MoySkladSyncService::sendConsultation($consultation);
        } catch (\Exception $e) {
            Log::error('Помилка відправки консультації в МойСклад', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage()
            ]);
        }

        // Возвращаем успешный ответ
        return response()->json([
            'status' => 'success',
            'message' => __('popup.consult_popup.thanks_message')
        ]);
    }
}
