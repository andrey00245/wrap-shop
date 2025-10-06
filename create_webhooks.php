<?php

require_once 'vendor/autoload.php';

// Инициализируем Laravel приложение
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

// Настройки
$accessToken = 'a3c824daa65594168869769cac94f44254049d15';
$webhookUrl = 'https://wrap-dev.vn.ua/webhook/moysklad';
$apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/webhook';

// Вебхуки для создания
$webhooks = [
    [
        'url' => $webhookUrl,
        'action' => 'CREATE',
        'entityType' => 'product'
    ],
    [
        'url' => $webhookUrl,
        'action' => 'UPDATE', 
        'entityType' => 'product'
    ],
    [
        'url' => $webhookUrl,
        'action' => 'DELETE',
        'entityType' => 'product'
    ]
];

echo "Создание вебхуков для МойСклад...\n\n";

foreach ($webhooks as $index => $webhook) {
    echo "Создание вебхука " . ($index + 1) . ": {$webhook['action']} для {$webhook['entityType']}\n";
    
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept-Encoding' => 'gzip',
            'Content-Type' => 'application/json'
        ])->post($apiUrl, $webhook);
        
        if ($response->successful()) {
            $data = $response->json();
            echo "✅ Успешно создан! ID: {$data['id']}\n";
            echo "   URL: {$data['url']}\n";
            echo "   Action: {$data['action']}\n";
            echo "   EntityType: {$data['entityType']}\n";
            echo "   Enabled: " . ($data['enabled'] ? 'Да' : 'Нет') . "\n\n";
        } else {
            echo "❌ Ошибка создания вебхука!\n";
            echo "   Статус: {$response->status()}\n";
            echo "   Ответ: {$response->body()}\n\n";
        }
    } catch (Exception $e) {
        echo "❌ Исключение: {$e->getMessage()}\n\n";
    }
}

echo "Готово!\n";
