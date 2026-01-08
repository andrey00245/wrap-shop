<?php

namespace App\Http\Controllers\Nova;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class FeedGeneratorController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->get();
        
        return view('nova.feed-generator.tool', [
            'categories' => $categories
        ]);
    }
}


