<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @return View
   */
  public function __invoke(): View
  {
    $products = Product::query()
      ->whereHas('media')
      ->whereHas('categories')
      ->with(['media', 'categories'])
      ->get();

    $mainCategories = Category::query()->whereNull('parent_id')->get();

    $categories = $products->take(5)->flatMap(function ($product) {
      return $product->categories;
    })->unique('id');
    return view('base.pages.products', compact(
        'categories',
        'products',
        'mainCategories'
      )
    );
  }
}
