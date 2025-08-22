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
        
        $languageId = match($language) {
            'uk' => Language::LANGUAGE_ID_UK,
            'en' => Language::LANGUAGE_ID_EN,
            'ru' => Language::LANGUAGE_ID_RU,
            default => Language::LANGUAGE_ID_UK,
        };

        $faqs = Faq::active()
            ->ordered()
            ->with(['translations' => function($query) use ($languageId) {
                $query->where('language_id', $languageId);
            }])
            ->get()
            ->map(function($faq) {
                $translation = $faq->translations->first();
                return [
                    'id' => $faq->id,
                    'question' => $translation ? $translation->question : '',
                    'answer' => $translation ? $translation->answer : '',
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