<?php

// Настройки
$accessToken = 'a3c824daa65594168869769cac94f44254049d15';
$apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/product';

echo "Проверка товаров в МойСклад...\n\n";

try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
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

        if (isset($data['meta']['size'])) {
            echo "Всего товаров: {$data['meta']['size']}\n";
        }

        if (isset($data['rows'])) {
            echo "Товаров в ответе: " . count($data['rows']) . "\n";

            if (count($data['rows']) > 0) {
                echo "\nПервый товар:\n";
                $firstProduct = $data['rows'][0];
                echo "ID: {$firstProduct['id']}\n";
                echo "Название: {$firstProduct['name']}\n";
                echo "Артикул: " . ($firstProduct['article'] ?? 'не указан') . "\n";
                echo "Обновлен: " . ($firstProduct['updated'] ?? 'не указано') . "\n";
            }
        } else {
            echo "❌ Нет товаров в ответе!\n";
        }
    } else {
        echo "❌ Ошибка получения товаров!\n";
        echo "Ответ: $response\n";
    }
} catch (Exception $e) {
    echo "❌ Исключение: {$e->getMessage()}\n";
}

echo "\nГотово!\n";

