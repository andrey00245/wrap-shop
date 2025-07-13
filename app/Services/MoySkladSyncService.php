<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use MoySklad\MoySklad;
use MoySklad\Entities\Documents\Orders\CustomerOrder;
use MoySklad\Entities\Products\Product as ApiProduct;
use MoySklad\Entities\Counterparty;

class MoySkladSyncService
{
    public static function sendOrder(Order $order): void
    {
        try {
            $sklad = MoySklad::getInstance(
                config('app.my_store.username'),
                config('app.my_store.password')
            );

            $response = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )->get('https://api.moysklad.ru/api/remap/1.2/entity/counterparty', [
                'filter' => 'email=' . $order->email,
            ]);

            dd($response->successful());
            if ($response->successful() && count($response->json('rows')) > 0) {
                $existing = $response->json('rows')[0];
                $counterpartyMeta = $existing['meta'];
            } else {
                // создаём контрагента через SDK
                $counterparty = Counterparty::create($sklad, [
                    'name'  => $order->first_name . ' ' . $order->last_name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                ]);
                $counterpartyMeta = $counterparty->meta->toArray();
            }

            $customerOrder = CustomerOrder::create($sklad, [
                'name'        => 'ORDER-' . $order->id,
                'description' => 'Заказ с сайта',
                'agent'       => ['meta' => $counterpartyMeta],
            ]);

            foreach ($order->products as $orderProduct) {
                $product = $orderProduct->product;

                if (!$product || !$product->external_id) {
                    Log::warning("Пропущен товар без external_id (order_id: {$order->id})");
                    continue;
                }

                $msProduct = ApiProduct::find($sklad, $product->external_id);

                $customerOrder->positions()->create([
                    'quantity'   => $orderProduct->quantity,
                    'price'      => $orderProduct->price * 100, // копейки
                    'assortment' => ['meta' => $msProduct->meta],
                ]);
            }

            Log::info("Заказ #{$order->id} успешно отправлен в МойСклад");
        } catch (\Throwable $e) {
            dd($e->getMessage());
            Log::error("Ошибка при отправке заказа #{$order->id} в МойСклад: " . $e->getMessage());
        }
    }
}
