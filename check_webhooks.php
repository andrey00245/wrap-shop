<?php

// Настройки
$accessToken = 'a3c824daa65594168869769cac94f44254049d15';
$apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/webhook';

echo "Проверка вебхуков в МойСклад...\n\n";

try {
    echo "Отправка запроса к API МойСклад...\n";
    echo "URL: $apiUrl\n";
    echo "Token: " . substr($accessToken, 0, 10) . "...\n\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Accept-Encoding: gzip',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP код: $httpCode\n";
    echo "Ошибка cURL: " . ($error ?: 'Нет') . "\n";
    echo "Размер ответа: " . strlen($response) . " байт\n";
    
    // Распаковываем gzip ответ
    $decodedResponse = gzdecode($response);
    if ($decodedResponse === false) {
        $decodedResponse = $response; // Если не gzip, используем как есть
    }
    
    echo "Размер после распаковки: " . strlen($decodedResponse) . " байт\n";
    echo "Ответ: " . substr($decodedResponse, 0, 500) . "\n\n";
    
    if ($error) {
        echo "❌ Ошибка cURL: $error\n";
        exit;
    }
    
    if ($httpCode >= 200 && $httpCode < 300) {
        $data = json_decode($decodedResponse, true);
        if (isset($data['rows'])) {
            echo "✅ Вебхуки найдены: " . count($data['rows']) . " шт.\n\n";
            
            foreach ($data['rows'] as $webhook) {
                echo "ID: {$webhook['id']}\n";
                echo "URL: {$webhook['url']}\n";
                echo "Action: {$webhook['action']}\n";
                echo "EntityType: {$webhook['entityType']}\n";
                echo "Enabled: " . ($webhook['enabled'] ? 'Да' : 'Нет') . "\n";
                echo "---\n";
            }
        } else {
            echo "❌ Неожиданный формат ответа!\n";
            echo "Структура данных: " . print_r(array_keys($data), true) . "\n";
        }
    } else {
        echo "❌ Ошибка получения вебхуков!\n";
        echo "Статус: $httpCode\n";
        echo "Ответ: $response\n";
    }
} catch (Exception $e) {
    echo "❌ Исключение: {$e->getMessage()}\n";
    echo "Трассировка: {$e->getTraceAsString()}\n";
}

echo "\nГотово!\n";
