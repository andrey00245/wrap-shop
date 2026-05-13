<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    /**
     * Відповідь «ще сторінка товарів» для infinite scroll. Не кешувати як HTML-документ:
     * інакше «Назад» з картки товару інколи підставляє цей JSON замість сторінки категорії.
     */
    protected function ajaxProductListingJson(LengthAwarePaginator $products): JsonResponse
    {
        return response()->json([
            'lastPage' => $products->lastPage(),
            'html' => view('base.pages.products.ajax-product-list', compact('products'))->render(),
        ])->withHeaders([
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Vary' => 'X-Requested-With',
        ]);
    }

    /**
     * HTML списку товарів — Vary, щоб кеш не змішував з JSON за тим самим URL.
     */
    protected function productListingIndexResponse(string $view, array $data): Response
    {
        return response()->view($view, $data)->header('Vary', 'X-Requested-With');
    }
}
