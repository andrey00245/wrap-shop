<?php


namespace App\Services;

use App\Models\ReportAvailability;
use App\Models\Product;
use App\Notifications\ProductAvailableNotification;

class ProductAvailabilityService
{
    public function notifyUsers(Product $product)
    {
        if ($product->stock <= 0) {
            return;
        }

        $reports = ReportAvailability::where('product_id', $product->id)->get();

        foreach ($reports as $report) {
            $report->notify(new ProductAvailableNotification($product));
            $report->delete();
        }
    }
}
