<?php

// Тестовые данные вебхука от МойСклад
$webhookData = [
    'events' => [
        [
            'entityType' => 'product',
            'action' => 'UPDATE',
            'entityId' => 'test-product-id'
        ]
    ]
];

$url = 'https://wrap-dev.vn.ua/webhook/moysklad';

echo "Отправка тестового вебхука...\n";
echo "URL: $url\n";
echo "Данные: " . json_encode($webhookData, JSON_PRETTY_PRINT) . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: MoySklad-Webhook/1.0'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP код: $httpCode\n";
echo "Ошибка: " . ($error ?: 'Нет') . "\n";
echo "Ответ: $response\n";

if ($httpCode === 200) {
    echo "✅ Тестовый вебхук отправлен успешно!\n";
} else {
    echo "❌ Ошибка отправки вебхука!\n";
}
