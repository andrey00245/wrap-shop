<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckboxService
{
    protected string $licenseKey;
    protected string $pinCode;
    protected string $baseUrl;

    public function __construct()
    {
        $this->licenseKey = env('CHECKBOX_LICENSE_KEY');
        $this->pinCode = env('CHECKBOX_PASSWORD');
        $this->baseUrl = 'https://api.checkbox.in.ua/api/v1';
    }

    protected function authenticate(): ?string
    {
        $response = Http::withHeaders([
            'X-License-Key' => $this->licenseKey,
        ])->post($this->baseUrl . '/cashier/signinPinCode', [
            'pin_code' => $this->pinCode,
        ]);

        if ($response->ok()) {
            return $response['access_token'];
        }

        Log::error('Ошибка авторизации в Checkbox', ['status' => $response->status(), 'body' => $response->body()]);
        return null;
    }

    public function sendReceipt(Order $order): bool
    {
        $token = $this->authenticate();

        if (!$token) {
            Log::error('Не удалось авторизоваться в Checkbox');
            $order->update([
                'checkbox_status' => 'auth_failed',
            ]);
            return false;
        }

        $goods = [];
        $totalCents = 0;

        foreach ($order->products as $product) {
            $priceCents = (int) round($product->pivot->price * 100);
            $quantity = (int) $product->pivot->quantity;
            $sumCents = $priceCents * $quantity;
            $totalCents += $sumCents;

            $goods[] = [
                'good' => [
                    'code' => (string) $product->id,
                    'name' => $product->name,
                    'price' => $priceCents,
                    'tax' => [8],
                ],
                'quantity' => $quantity * 1000,
            ];
        }

        $phone = preg_replace('/\D/', '', $order->phone);
        if (substr($phone, 0, 3) !== '380') {
            $phone = '380' . ltrim($phone, '0');
        }

        $receiptId = (string) Str::uuid();

        $receiptData = [
            'id' => $receiptId,
            'goods' => $goods,
            'payments' => [
                [
                    'type' => 'CASHLESS',
                    'value' => $totalCents,
                ],
            ],
            'delivery' => [
                'email' => $order->email,
                'phone' => $phone,
            ],
        ];

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->baseUrl . '/receipts/sell', $receiptData);

        if ($response->ok()) {
            $data = $response->json();

            $order->update([
                'checkbox_receipt_id' => $receiptId,
                'checkbox_status'     => 'success',
                'checkbox_response'   => $data,
            ]);

            Log::info('Чек успешно отправлен в Checkbox', ['order_id' => $order->id]);
            return true;
        }

        $order->update([
            'checkbox_receipt_id' => $receiptId,
            'checkbox_status'     => 'failed',
            'checkbox_response'   => $response->json(),
        ]);

        Log::error('Ошибка отправки чека в Checkbox', [
            'order_id' => $order->id,
            'response' => $response->body(),
        ]);

        return false;
    }
}
