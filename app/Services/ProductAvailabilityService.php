<?php


namespace App\Services;

use App\Models\ReportAvailability;
use App\Models\Product;
use App\Notifications\ProductAvailableNotification;
use Illuminate\Support\Facades\Http;

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
            $this->sendStockAvailableSms($product);
            $report->delete();
        }
    }


    private function sendStockAvailableSms(Product $product): void
    {
        /**@var ReportAvailability * */
        $reports = ReportAvailability::where('product_id', $product->id)->get();

        $longUrl = route('products.show', ['product' => $product->id]);

        foreach ($reports as $report) {

            if ($report->phone) {
                $shortUrl = $this->shortenUrlWithIsGd($longUrl, 'wrap_shop_' . random_int(1000, 9999));
                $message = "Вітаємо! Товар, яким ви цікавились, вже в наявності. {$shortUrl}";
                app(\App\Services\TurboSMSService::class)->sendSms(
                    [$report->phone],
                    $message
                );
            }

        }
    }

    private function shortenUrlWithIsGd(string $url, string $alias = ''): ?string
    {
        if (app()->environment('local')) {
            $url = 'https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin';
        }

        $params = [
            'format'   => 'simple',
            'url'      => $url,
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
