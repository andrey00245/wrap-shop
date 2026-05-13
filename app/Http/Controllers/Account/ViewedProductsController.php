<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ViewedProductsController extends Controller
{
    public function index()
    {
        $ids = session()->get('viewProducts', []);
        if (! is_array($ids)) {
            $ids = [];
        }
        $ids = array_values(array_unique(array_map('intval', $ids)));

        $viewedProducts = $ids === []
            ? collect()
            : Product::query()
                ->where('is_active', 1)
                ->whereIn('id', $ids)
                ->get()
                ->sort(function (Product $a, Product $b) use ($ids): int {
                    $sa = $a->stock > 0 ? 0 : 1;
                    $sb = $b->stock > 0 ? 0 : 1;
                    if ($sa !== $sb) {
                        return $sa <=> $sb;
                    }

                    return array_search($a->id, $ids, true) <=> array_search($b->id, $ids, true);
                })
                ->values();

        return view('base.pages.account.viewed-products', [
            'viewedProducts' => $viewedProducts,
        ]);
    }
}
