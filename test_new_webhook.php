<?php

// Настройки
$accessToken = 'a3c824daa65594168869769cac94f44254049d15';
$webhookId = '8db23b44-9cb6-11f0-0a80-02d7006b1ea2'; // UPDATE webhook ID

echo "Тестирование нового вебхука в МойСклад...\n";
echo "Webhook ID: $webhookId\n\n";

try {
    // Получаем информацию о вебхуке
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.moysklad.ru/api/remap/1.2/entity/webhook/$webhookId");
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
    
    echo "HTTP код: $httpCode\n";
    echo "Ошибка: " . ($error ?: 'Нет') . "\n";
    
    if ($httpCode >= 200 && $httpCode < 300) {
        $decodedResponse = gzdecode($response);
        if ($decodedResponse === false) {
            $decodedResponse = $response;
        }
        
        $data = json_decode($decodedResponse, true);
        
        echo "Вебхук:\n";
        echo "ID: {$data['id']}\n";
        echo "URL: {$data['url']}\n";
        echo "Action: {$data['action']}\n";
        echo "EntityType: {$data['entityType']}\n";
        echo "Enabled: " . ($data['enabled'] ? 'Да' : 'Нет') . "\n";
        echo "Created: {$data['created']}\n";
        echo "Updated: {$data['updated']}\n";
        
        if (isset($data['lastRequest'])) {
            echo "Last Request: {$data['lastRequest']}\n";
        } else {
            echo "Last Request: НИКОГДА\n";
        }
        
        if (isset($data['lastResponse'])) {
            echo "Last Response: {$data['lastResponse']}\n";
        } else {
            echo "Last Response: НИКОГДА\n";
        }
        
    } else {
        echo "❌ Ошибка получения вебхука!\n";
        echo "Ответ: $response\n";
    }
} catch (Exception $e) {
    echo "❌ Исключение: {$e->getMessage()}\n";
}

echo "\nГотово!\n";
