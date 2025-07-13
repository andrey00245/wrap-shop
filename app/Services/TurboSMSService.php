<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurboSMSService
{
    protected $token;

    public function __construct()
    {
        $this->token = env('TURBOSMS_TOKEN');
    }

    public function sendSms(array $recipients, string $message): array
    {
        $payload = [
            'recipients' => $recipients,
            'sms' => [
                'sender' => 'Wrap.Shop',
                'text' => $message,
            ],
            'token' => $this->token,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ])->post('https://api.turbosms.ua/message/send.json', $payload);

        $data = $response->json();

        $successStatuses = ['OK', 'SUCCESS_MESSAGE_SENT'];

        if ($response->failed() || !in_array($data['response_status'] ?? '', $successStatuses)) {
            throw new \Exception('TurboSMS error: ' . ($data['response_status'] ?? 'Unknown error'));
        }

        return $data;
    }
}
