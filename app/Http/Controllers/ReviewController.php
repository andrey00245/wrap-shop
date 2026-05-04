<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $baseQuery = Review::query()
            ->where('is_active', true)
            ->where('moderation_status', 'approved');

        $reviews = (clone $baseQuery)
            ->with('product')
            ->orderByDesc('created_at')
            ->paginate(12);

        $averageRaw = (float) $baseQuery->avg('rating');
        $maxRating = (float) (clone $baseQuery)->max('rating');
        $averageRating = round(
            Product::normalizeReviewAverageForFiveStarScale($averageRaw, $maxRating),
            1
        );
        $totalReviews = (int) $baseQuery->count();

        return view('base.pages.reviews.index', compact('reviews', 'averageRating', 'totalReviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'name' => 'required|string|max:255',
            'text' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
            'photo' => 'nullable|image|max:15360',
        ], [
            'product_id.exists' => __('popup.reviews_popup.validation.product_invalid'),
            'name.required' => __('popup.reviews_popup.validation.name_required'),
            'name.string' => __('popup.reviews_popup.validation.name_string'),
            'name.max' => __('popup.reviews_popup.validation.name_max'),
            'text.required' => __('popup.reviews_popup.validation.text_required'),
            'text.string' => __('popup.reviews_popup.validation.text_string'),
            'text.max' => __('popup.reviews_popup.validation.text_max'),
            'rating.required' => __('popup.reviews_popup.validation.rating_required'),
            'rating.integer' => __('popup.reviews_popup.validation.rating_integer'),
            'rating.between' => __('popup.reviews_popup.validation.rating_between'),
            'photo.uploaded' => __('popup.reviews_popup.validation.photo_uploaded'),
            'photo.image' => __('popup.reviews_popup.validation.photo_image'),
            'photo.max' => __('popup.reviews_popup.validation.photo_max'),
        ]);

        $payload = [
            'product_id' => $validated['product_id'] ?? null,
            'name' => $validated['name'],
            'text' => $validated['text'],
            'rating' => $validated['rating'],
            'is_active' => false,
            'moderation_status' => 'pending',
            'moderated_at' => null,
        ];

        if ($request->hasFile('photo')) {
            $payload['photo'] = $request->file('photo')->store('reviews', 'public');
        }

        $review = Review::create($payload);

        return response()->json([
            'message' => __('popup.reviews_popup.success_message'),
            'review' => $review,
        ]);
    }
}
