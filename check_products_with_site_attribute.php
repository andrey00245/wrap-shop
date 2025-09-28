<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

$accessToken = 'a3c824daa65594168869769cac94f44254049d15';
$apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/product';

echo "Проверка товаров с атрибутом 'Сайт'...\n\n";

try {
    $response = Http::withToken($accessToken)
        ->withHeaders([
            'Accept-Encoding' => 'gzip',
            'Content-Type' => 'application/json'
        ])
        ->get($apiUrl, [
            'limit' => 500,
            'expand' => 'attributes'
        ]);

    if ($response->successful()) {
        $data = $response->json();

        if (isset($data['rows'])) {
            echo "Найдено товаров: " . count($data['rows']) . "\n\n";

            foreach ($data['rows'] as $index => $product) {
                echo "Товар " . ($index + 1) . ":\n";
                echo "ID: {$product['id']}\n";
                echo "Название: {$product['name']}\n";
                echo "Артикул: " . ($product['article'] ?? 'не указан') . "\n";

                if (isset($product['attributes'])) {
                    $hasSiteAttribute = false;
                    dd($product['attributes']);
                    foreach ($product['attributes'] as $attribute) {
                        if (isset($attribute['id']) && $attribute['id'] === '10726') {
                            $hasSiteAttribute = true;
                            echo "Атрибут 'Сайт': " . ($attribute['value']['name'] ?? 'не указан') . "\n";
                            break;
                        }
                    }

                    if (!$hasSiteAttribute) {
                        echo "Атрибут 'Сайт': НЕТ\n";
                    }
                } else {
                    echo "Атрибуты: НЕТ\n";
                }

                echo "---\n";
            }
        } else {
            echo "❌ Неожиданный формат ответа!\n";
        }
    } else {
        echo "❌ Ошибка получения товаров! Статус: " . $response->status() . "\n";
        echo "Ответ: " . $response->body() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Исключение: {$e->getMessage()}\n";
}

echo "\nГотово!\n";
