<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductAvailabilityService;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    public function updated(Product $product): void
    {
        if (! $this->stockBecameAvailable($product)) {
            return;
        }

        try {
            app(ProductAvailabilityService::class)->notifyUsers($product);
        } catch (\Throwable $e) {
            // Не валимо синк цін/залишків через SMTP/SMS (wrap.shop:587 timeout тощо).
            Log::error('ProductObserver: не вдалося сповістити про наявність товару', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function stockBecameAvailable(Product $product): bool
    {
        return $product->wasChanged('stock') &&
            (int) $product->getOriginal('stock') === 0 &&
            $product->stock > 0;
    }
}
