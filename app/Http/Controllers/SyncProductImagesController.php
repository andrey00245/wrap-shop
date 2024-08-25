<?php

namespace App\Http\Controllers;

use App\Services\ProductService;

class SyncProductImagesController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @throws \Exception
     */
    public function updateProducts()
    {
        $this->productService->processImages();
        return response()->json(['status' => 'success']);
    }
}
