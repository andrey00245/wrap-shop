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
        $this->licenseKey = config('services.checkbox.license_key');
        $this->pinCode = config('services.checkbox.password');
        $this->baseUrl = config('services.checkbox.base_url');
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

    protected function checkShiftStatus(string $token): bool
    {
        // Проверяем статус смены через правильный endpoint (множественное число)
        // Документация: https://api.checkbox.in.ua/api/redoc#tag/Zmini/operation/get_shifts_api_v1_shifts_get
        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->get($this->baseUrl . '/shifts');

        Log::info('Checkbox API Debug - проверка смены', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json()
        ]);

        if ($response->ok()) {
            $data = $response->json();
            
            if ($data === null) {
                Log::warning('API вернул null для проверки смены');
                return false;
            }

            // Если в ответе есть массив смен, проверяем есть ли открытые
            if (isset($data['results']) && is_array($data['results'])) {
                $shifts = $data['results'];
                $openShifts = array_filter($shifts, function($shift) {
                    return ($shift['status'] ?? '') === 'OPENED';
                });
                
                $hasOpenShift = count($openShifts) > 0;
                Log::info('Статус смены в Checkbox', [
                    'total_shifts' => count($shifts),
                    'open_shifts' => count($openShifts),
                    'has_open_shift' => $hasOpenShift,
                    'shift_statuses' => array_map(function($shift) {
                        return [
                            'id' => $shift['id'] ?? 'N/A',
                            'status' => $shift['status'] ?? 'N/A',
                            'serial' => $shift['serial'] ?? 'N/A'
                        ];
                    }, $shifts)
                ]);
                
                return $hasOpenShift;
            }
            
            // Если ответ не массив, проверяем поля статуса
            $state = $data['state'] ?? $data['status'] ?? null;
            return $state === 'OPENED' || $state === 'OPEN';
        }

        Log::warning('Не удалось проверить статус смены в Checkbox', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        return false;
    }

    protected function openShift(string $token): bool
    {
        // Сначала попробуем найти существующую закрытую смену и открыть её
        $existingShift = $this->findExistingShift($token);
        
        if ($existingShift) {
            Log::info('Найдена существующая смена, пытаемся открыть', ['shift_id' => $existingShift['id']]);
            if ($this->tryOpenExistingShift($token, $existingShift['id'])) {
                return true;
            }
            Log::info('Не удалось открыть существующую смену, создаем новую');
        } else {
            Log::info('Существующая смена не найдена, создаем новую');
        }
        $shiftId = \Str::uuid()->toString();
        
        $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-Client-Name' => 'Wrap Shop',
                'X-Client-Version' => '1.0.0',
                'X-License-Key' => config('services.checkbox.license_key'),
            ])
            ->post($this->baseUrl . '/shifts', [
                'id' => $shiftId,
                'auto_close_at' => now()->addHours(8)->toISOString(), // Автозакрытие через 8 часов
            ]);

        Log::info('Checkbox API Debug - создание смены', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json()
        ]);

        if (in_array($response->status(), [200, 201, 202])) {
            $data = $response->json();
            $shiftId = $data['id'] ?? $shiftId;
            
            Log::info('Смена создана в Checkbox', ['shift_id' => $shiftId, 'status' => $data['status'] ?? 'UNKNOWN']);
            
            // Ждем пока смена перейдет в статус OPENED
            return $this->waitForShiftToOpen($token, $shiftId);
        }

        Log::error('Ошибка создания смены в Checkbox', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        return false;
    }

    protected function findExistingShift(string $token): ?array
    {
        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->get($this->baseUrl . '/shifts');

        if ($response->ok()) {
            $data = $response->json();
            
            if (isset($data['results']) && is_array($data['results'])) {
                $shifts = $data['results'];
                
                // Ищем первую закрытую смену (самую последнюю)
                $closedShifts = array_filter($shifts, function($shift) {
                    return ($shift['status'] ?? '') === 'CLOSED';
                });
                
                if (!empty($closedShifts)) {
                    // Берем самую последнюю закрытую смену
                    $latestShift = array_values($closedShifts)[0];
                    Log::info('Найдена закрытая смена', [
                        'shift_id' => $latestShift['id'],
                        'serial' => $latestShift['serial'] ?? 'N/A',
                        'closed_at' => $latestShift['closed_at'] ?? 'N/A'
                    ]);
                    return $latestShift;
                }
            }
        }

        Log::info('Существующие смены не найдены');
        return null;
    }

    protected function tryOpenExistingShift(string $token, string $shiftId): bool
    {
        // Пытаемся открыть существующую смену
        $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-Client-Name' => 'Wrap Shop',
                'X-Client-Version' => '1.0.0',
                'X-License-Key' => config('services.checkbox.license_key'),
            ])
            ->post($this->baseUrl . '/shifts/' . $shiftId . '/open');

        Log::info('Checkbox API Debug - открытие существующей смены', [
            'shift_id' => $shiftId,
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json()
        ]);

        if (in_array($response->status(), [200, 201, 202])) {
            Log::info('Существующая смена успешно открыта', ['shift_id' => $shiftId]);
            return true;
        }

        // Если не удалось открыть существующую, создаем новую
        Log::warning('Не удалось открыть существующую смену, создаем новую', [
            'shift_id' => $shiftId,
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        return false; // Вернем false, чтобы создать новую смену
    }

    protected function waitForShiftToOpen(string $token, string $shiftId): bool
    {
        $maxAttempts = 30; // Максимум 30 попыток (30 секунд)
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->get($this->baseUrl . '/shifts/' . $shiftId);

            if ($response->ok()) {
                $data = $response->json();
                $status = $data['status'] ?? 'UNKNOWN';
                
                Log::info('Проверка статуса смены', [
                    'shift_id' => $shiftId,
                    'status' => $status,
                    'attempt' => $attempt + 1
                ]);

                if ($status === 'OPENED') {
                    Log::info('Смена успешно открыта', ['shift_id' => $shiftId]);
                    return true;
                }

                if ($status === 'CLOSED') {
                    Log::error('Смена была закрыта до открытия', ['shift_id' => $shiftId]);
                    return false;
                }

                // Логируем промежуточные статусы
                if (in_array($status, ['CREATED', 'PENDING'])) {
                    Log::info('Смена в процессе открытия', [
                        'shift_id' => $shiftId,
                        'status' => $status,
                        'attempt' => $attempt + 1
                    ]);
                }

                // Ждем 1 секунду перед следующей проверкой
                sleep(1);
                $attempt++;
            } else {
                Log::warning('Ошибка при проверке статуса смены', [
                    'shift_id' => $shiftId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                sleep(1);
                $attempt++;
            }
        }

        Log::error('Таймаут ожидания открытия смены', ['shift_id' => $shiftId, 'attempts' => $maxAttempts]);
        return false;
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

        // Проверяем статус смены
        $shiftStatus = $this->checkShiftStatus($token);
        Log::info('Статус смены', ['is_open' => $shiftStatus]);
        
        if (!$shiftStatus) {
            Log::info('Смена не открыта, пытаемся открыть...');

            if (!$this->openShift($token)) {
                Log::error('Не удалось открыть смену в Checkbox');
                $order->update([
                    'checkbox_status' => 'shift_failed',
                    'checkbox_response' => 'Не удалось открыть кассовую смену ❌',
                ]);
                return false;
            }

            // Ждем немного, чтобы смена успела открыться
            sleep(2);
        }

        $goods = [];
        $totalCents = 0;
        
        Log::info('Обработка товаров заказа', [
            'order_id' => $order->id,
            'products_count' => $order->products->count(),
            'products' => $order->products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->pivot->price ?? 'N/A',
                    'quantity' => $product->pivot->quantity ?? 'N/A'
                ];
            })->toArray()
        ]);
        
        foreach ($order->products as $product) {
            $priceCents = (int)round($product->pivot->price * 100);
            $quantity = (int)$product->pivot->quantity;
            $sumCents = $priceCents * $quantity;
            $totalCents += $sumCents;
            $goods[] = [
                'good'     => [
                    'code'  => (string)$product->id,
                    'name'  => $product->name,
                    'price' => $priceCents,
                    'tax'   => [8],
                ],
                'quantity' => $quantity * 1000,
            ];
        }
        
        if (empty($goods)) {
            Log::error('В заказе нет товаров для отправки в Checkbox', [
                'order_id' => $order->id,
                'products_count' => $order->products->count()
            ]);
            $order->update([
                'checkbox_status' => 'failed',
                'checkbox_response' => 'В заказе нет товаров для фискализации ❌',
            ]);
            return false;
        }

        $phone = preg_replace('/\D/', '', $order->phone); // Убирает все, кроме цифр

        if (substr($phone, 0, 3) !== '380') {
            $phone = '380' . ltrim($phone, '0');
        }

        $receiptId = (string) Str::uuid();

        $receiptData = [
            'id'       => $receiptId,
            'goods'    => $goods,
            'payments' => [
                ['type' => 'CASHLESS', 'value' => $totalCents,],
            ],
            'delivery' => ['email' => $order->email, 'phone' => $phone,],
        ];

        $response = Http::withToken($token)->withHeaders(['Content-Type' => 'application/json'])->post($this->baseUrl . '/receipts/sell', $receiptData);

        // Если получили ошибку "Зміну не відкрито", пытаемся еще раз открыть смену
        if ($response->status() === 400 && str_contains($response->body(), 'Зміну не відкрито')) {
            Log::warning('Получили ошибку "Зміну не відкрито", пытаемся открыть смену повторно...');

            if ($this->openShift($token)) {
                sleep(3); // Ждем дольше

                // Повторяем отправку чека
                $response = Http::withToken($token)->withHeaders(['Content-Type' => 'application/json'])->post($this->baseUrl . '/receipts/sell', $receiptData);
                
                Log::info('Повторная попытка отправки чека', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        }

        if (in_array($response->status(), [200, 201, 202]) && $response['status'] ?? null === 'CREATED') {
            $userMessage = 'Чек успішно створено ✅';
            $data = $response->json();

            // Извлекаем fiscal_code и tax_url
            $fiscalCode = $data['fiscal_code'] ?? null;
            $taxUrl = $data['tax_url'] ?? null;

            $mailId = null;

            if ($taxUrl) {
                // Парсим query-параметры tax_url
                $query = parse_url($taxUrl, PHP_URL_QUERY);
                parse_str($query, $params);

                // ID, который приходит на почту, находится в параметре 'id'
                $mailId = $params['id'] ?? null;
            }

            $order->update([
                'checkbox_receipt_id' => $fiscalCode .' | '. $mailId, // UUID API
                'checkbox_status'     => 'success',
                'checkbox_response'   => $userMessage,
            ]);

            Log::info('Чек успешно отправлен в Checkbox', [
                'order_id' => $order->id,
                'fiscal_code' => $fiscalCode,
                'mail_id' => $mailId,
            ]);

            return true;
        }

        $errorMessage = $response->json('message') ?? 'Сталася помилка при створенні чека ❌';
        
        // Если ошибка связана с закрытой сменой, даем более понятное сообщение
        if (str_contains($errorMessage, 'Зміну не відкрито')) {
            $errorMessage = 'Кассова зміна не відкрита. Будь ласка, відкрийте зміну в додатку Checkbox та спробуйте ще раз. ❌';
        }
        
        $order->update([
            'checkbox_receipt_id' => $receiptId,
            'checkbox_status'     => 'failed',
            'checkbox_response'   => $errorMessage,
        ]);

        Log::error('Ошибка отправки чека в Checkbox', [
            'order_id' => $order->id,
            'status' => $response->status(),
            'response' => $response->body(),
            'error_message' => $errorMessage,
        ]);

        return false;
    }
}
