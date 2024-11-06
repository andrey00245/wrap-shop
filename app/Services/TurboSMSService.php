<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurboSMSService
{
    protected $login;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->login = env('TURBOSMS_LOGIN');
        $this->password = env('TURBOSMS_PASSWORD');
        $this->token = env('TURBOSMS_TOKEN');
    }

    /**
     * Отправка SMS сообщения
     *
     * @param array  $recipients Массив номеров телефонов
     * @param string $message Сообщение
     *
     * @return array Ответ от API
     * @throws \JsonException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function sendSms($recipients, $message)
    {
        $payload = [
            'recipients' => ['380997735297'], // Массив в формате JSON
            'sms'        => json_encode([
                'sender' => 'Wrap.Shop',
                'text'   => 'test',
            ], JSON_THROW_ON_ERROR),
            'token' => $this->token,
        ];

        $formData = http_build_query($payload);
        $authToken = base64_encode("{$this->login}:{$this->password}");


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.turbosms.ua/message/send.json', $formData);

        return $response->json();
    }
}
