<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductAvailabilityService;

class ProductObserver
{
    public function updating(Product $product): void
    {
        if ($this->stockBecameAvailable($product)) {
            app(ProductAvailabilityService::class)->notifyUsers($product);
        }
    }

    private function stockBecameAvailable(Product $product): bool
    {
        return $product->isDirty('stock') &&
            (int) $product->getOriginal('stock') === 0 &&
            $product->stock > 0;
    }
}
