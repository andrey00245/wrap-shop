<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $baseQuery = Review::query()
            ->where('is_active', true);

        $reviews = (clone $baseQuery)
            ->with('product')
            ->orderByDesc('created_at')
            ->paginate(12);

        $averageRating = round((float) $baseQuery->avg('rating'), 1);
        $totalReviews = (int) $baseQuery->count();

        return view('base.pages.reviews.index', compact('reviews', 'averageRating', 'totalReviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'text' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ], [
            'name.required' => __('popup.reviews_popup.validation.name_required'),
            'name.string' => __('popup.reviews_popup.validation.name_string'),
            'name.max' => __('popup.reviews_popup.validation.name_max'),
            'text.required' => __('popup.reviews_popup.validation.text_required'),
            'text.string' => __('popup.reviews_popup.validation.text_string'),
            'text.max' => __('popup.reviews_popup.validation.text_max'),
            'rating.required' => __('popup.reviews_popup.validation.rating_required'),
            'rating.integer' => __('popup.reviews_popup.validation.rating_integer'),
            'rating.between' => __('popup.reviews_popup.validation.rating_between'),
        ]);

        $review = Review::create($validated);

        return response()->json([
            'message' => __('popup.reviews_popup.success_message'),
            'review' => $review,
        ]);
    }
}
