<?php

require_once 'vendor/autoload.php';

use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

// Инициализируем Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Тестируем синхронизацию стока для товара из логов
$entityId = '531b1425-aff8-11f0-0a80-16c400208dc2';

echo "Тестирование синхронизации стока для товара: {$entityId}\n";

try {
    $productService = new ProductService();
    $productService->syncProductStock($entityId);
    
    echo "Синхронизация стока завершена успешно!\n";
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}


