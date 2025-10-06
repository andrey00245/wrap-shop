<?php

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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhook));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Accept-Encoding: gzip',
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            echo "❌ Ошибка cURL: $error\n\n";
            continue;
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            $data = json_decode($response, true);
            echo "✅ Успешно создан! ID: {$data['id']}\n";
            echo "   URL: {$data['url']}\n";
            echo "   Action: {$data['action']}\n";
            echo "   EntityType: {$data['entityType']}\n";
            echo "   Enabled: " . ($data['enabled'] ? 'Да' : 'Нет') . "\n\n";
        } else {
            echo "❌ Ошибка создания вебхука!\n";
            echo "   Статус: $httpCode\n";
            echo "   Ответ: $response\n\n";
        }
    } catch (Exception $e) {
        echo "❌ Исключение: {$e->getMessage()}\n\n";
    }
}

echo "Готово!\n";
