<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('base.pages.main', [
            'products' => Product::query()->with(['media','categories'])->get(),
        ]);
    }
}
