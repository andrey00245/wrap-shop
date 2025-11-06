<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

$accessToken = 'a3c824daa65594168869769cac94f44254049d15';
$productId = 'a3f815bf-9c97-11f0-0a80-1a11002429e8';
$apiUrl = "https://api.moysklad.ru/api/remap/1.2/entity/product/$productId";

echo "Проверка конкретного товара...\n";
echo "Product ID: $productId\n\n";

try {
    $response = Http::withToken($accessToken)
        ->withHeaders([
            'Accept-Encoding' => 'gzip',
            'Content-Type' => 'application/json'
        ])
        ->get($apiUrl, [
            'expand' => 'attributes'
        ]);

    if ($response->successful()) {
        $product = $response->json();

        echo "Товар найден:\n";
        echo "ID: {$product['id']}\n";
        echo "Название: {$product['name']}\n";
        echo "Артикул: " . ($product['article'] ?? 'не указан') . "\n";
        echo "Обновлен: " . ($product['updated'] ?? 'не указано') . "\n";
        echo "Активен: " . ($product['archived'] ? 'Нет' : 'Да') . "\n";

        if (isset($product['attributes'])) {
            echo "\nАтрибуты (" . count($product['attributes']) . " шт.):\n";
            $hasSiteAttribute = false;
            foreach ($product['attributes'] as $attribute) {
                echo "  - ID: {$attribute['id']}\n";
                echo "    Название: " . ($attribute['name'] ?? 'не указано') . "\n";
                if (isset($attribute['value'])) {
                    if (is_array($attribute['value'])) {
                        echo "    Значение: " . json_encode($attribute['value']) . "\n";
                    } else {
                        echo "    Значение: {$attribute['value']}\n";
                    }
                } else {
                    echo "    Значение: не указано\n";
                }

                // Проверяем атрибут "Сайт"
                if (isset($attribute['id']) && $attribute['id'] === '10726') {
                    $hasSiteAttribute = true;
                    echo "    *** ЭТО АТРИБУТ 'САЙТ' ***\n";
                }
                echo "\n";
            }

            if (!$hasSiteAttribute) {
                echo "❌ Атрибут 'Сайт' (ID: 10726) НЕ НАЙДЕН\n";
            } else {
                echo "✅ Атрибут 'Сайт' найден!\n";
            }
        } else {
            echo "\n❌ У товара нет атрибутов\n";
        }

    } else {
        echo "❌ Ошибка получения товара! Статус: " . $response->status() . "\n";
        echo "Ответ: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Исключение: {$e->getMessage()}\n";
}

echo "\nГотово!\n";

