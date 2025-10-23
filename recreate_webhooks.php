<?php

// Настройки
$accessToken = 'a3c824daa65594168869769cac94f44254049d15';
$webhookUrl = 'https://wrap-dev.vn.ua/webhook/moysklad';
$apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/webhook';

echo "Пересоздание вебхуков...\n\n";

// Сначала удалим старые вебхуки
$webhookIds = [
    'd26245a7-9c94-11f0-0a80-0b0f0067eb4f', // CREATE
    'd28e0766-9c94-11f0-0a80-1a1200667b80', // UPDATE
    'd2ab027e-9c94-11f0-0a80-0f590068515c'  // DELETE
];

echo "Удаление старых вебхуков...\n";
foreach ($webhookIds as $webhookId) {
    echo "Удаление вебхука: $webhookId\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$apiUrl/$webhookId");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Accept-Encoding: gzip',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        echo "✅ Удален\n";
    } else {
        echo "❌ Ошибка удаления: $httpCode\n";
    }
}

echo "\nСоздание новых вебхуков...\n";

$webhooksToCreate = [
    ['action' => 'CREATE', 'entityType' => 'product'],
    ['action' => 'UPDATE', 'entityType' => 'product'],
    ['action' => 'DELETE', 'entityType' => 'product'],
];

foreach ($webhooksToCreate as $index => $webhookConfig) {
    echo "Создание вебхука " . ($index + 1) . ": {$webhookConfig['action']} для {$webhookConfig['entityType']}\n";

    $payload = json_encode([
        'url'        => $webhookUrl,
        'action'     => $webhookConfig['action'],
        'entityType' => $webhookConfig['entityType'],
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
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
    } elseif ($httpCode >= 200 && $httpCode < 300) {
        $data = json_decode($response, true);
        echo "✅ Успешно создан! ID: {$data['id']}\n";
        echo "   URL: {$data['url']}\n";
        echo "   Action: {$data['action']}\n";
        echo "   EntityType: {$data['entityType']}\n";
        echo "   Enabled: " . ($data['enabled'] ? 'Да' : 'Нет') . "\n\n";
    } else {
        echo "❌ Ошибка создания вебхука! Статус: $httpCode\n";
        echo "   Ответ: " . $response . "\n\n";
    }
}

echo "Готово!\n";
