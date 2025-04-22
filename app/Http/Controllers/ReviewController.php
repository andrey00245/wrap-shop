<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'text' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review = Review::create($validated);

        return response()->json([
            'message' => 'Дякуємо за відгук!',
            'review' => $review
        ]);
    }
}
