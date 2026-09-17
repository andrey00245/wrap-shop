<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ReportAvailability;
use App\Notifications\ProductAvailableNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProductAvailabilityService
{
    public function notifyUsers(Product $product): void
    {
        if ($product->stock <= 0) {
            return;
        }

        $reports = ReportAvailability::where('product_id', $product->id)->get();

        foreach ($reports as $report) {
            try {
                if ($report->email) {
                    Notification::route('mail', $report->email)
                        ->notify(new ProductAvailableNotification($product, $report->name ?? ''));
                }

                $this->sendStockAvailableSms($report, $product);
                $report->delete();
            } catch (\Throwable $e) {
                Log::error('ProductAvailabilityService: помилка сповіщення про наявність', [
                    'product_id' => $product->id,
                    'report_id' => $report->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }

    private function sendStockAvailableSms(ReportAvailability $report, Product $product): void
    {
        if (! $report->phone) {
            return;
        }

        $longUrl = route('products.show', ['product' => $product->id]);
        $shortUrl = $this->shortenUrlWithIsGd($longUrl, 'wrap_shop_'.random_int(1000, 9999));
        $message = "Вітаємо! Товар, яким ви цікавились, вже в наявності. {$shortUrl}";

        app(TurboSMSService::class)->sendSms(
            [$report->phone],
            $message
        );
    }

    private function shortenUrlWithIsGd(string $url, string $alias = ''): ?string
    {
        $params = [
            'format' => 'simple',
            'url' => $url,
            'shorturl' => $alias,
        ];

        $response = Http::get('https://is.gd/create.php', $params);

        if ($response->successful()) {
            $body = $response->body();

            return str_starts_with($body, 'Error:') ? null : $body;
        }

        return null;
    }
}
