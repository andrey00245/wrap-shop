<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $language = $request->get('lang', 'uk');
        
        // Устанавливаем локаль для получения переводов
        app()->setLocale($language);
        
        $faqs = Faq::active()
            ->ordered()
            ->get()
            ->map(function($faq) use ($language) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->getTranslation('question', $language),
                    'answer' => $faq->getTranslation('answer', $language),
                    'order' => $faq->order,
                ];
            })
            ->filter(function($faq) {
                return !empty($faq['question']) && !empty($faq['answer']);
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $faqs,
        ]);
    }
}