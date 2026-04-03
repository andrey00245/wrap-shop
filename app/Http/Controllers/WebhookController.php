<?php

namespace App\Http\Controllers;

use App\Helpers\MoySkladApiHelper;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Обработка вебхуков от МойСклад
     */
    public function handle(Request $request)
    {
        try {
            // Логируем входящий вебхук
            Log::info('МойСклад вебхук получен', [
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
            ]);

            // Получаем данные из вебхука
            $data = $request->all();

            // Проверяем, что это вебхук от МойСклад
            if (! $this->isValidMoySkladWebhook($request)) {
                Log::warning('Неверный вебхук от МойСклад', ['data' => $data]);

                return response()->json(['error' => 'Invalid webhook'], 400);
            }

            // Обрабатываем вебхук
            $this->processWebhook($data);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Ошибка обработки вебхука МойСклад', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all(),
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Проверка валидности вебхука от МойСклад
     */
    private function isValidMoySkladWebhook(Request $request): bool
    {
        $data = $request->all();
        $userAgent = $request->header('User-Agent', '');

        // Логируем данные для отладки
        Log::info('Проверка валидности вебхука', [
            'data' => $data,
            'user_agent' => $userAgent,
            'method' => $request->method(),
            'request_id' => $request->get('requestId'),
        ]);

        // Проверяем User-Agent от МойСклад
        if (strpos($userAgent, 'MoySklad webhook') !== false) {
            Log::info('Вебхук от МойСклад подтвержден по User-Agent');

            return true;
        }

        // Для тестирования принимаем любые POST запросы
        if ($request->method() === 'POST') {
            Log::info('Принят POST запрос для тестирования');

            return true;
        }

        // Проверяем, что запрос содержит данные о товаре
        return isset($data['events']) || isset($data['entityType']) || isset($data['action']);
    }

    /**
     * Обработка данных вебхука
     */
    private function processWebhook(array $data): void
    {
        Log::info('Обработка вебхука МойСклад', ['data' => $data]);

        // Проверяем, что это реальный вебхук от МойСклад
        if (! isset($data['events']) && ! isset($data['entityType']) && ! isset($data['action'])) {
            Log::info('Тестовый запрос - пропускаем обработку');

            return;
        }

        // МойСклад может отправлять данные в разных форматах
        if (isset($data['events'])) {
            // Обрабатываем массив событий
            foreach ($data['events'] as $event) {
                $this->processEvent($event);
            }
        } else {
            // Обрабатываем одиночное событие
            $this->processEvent($data);
        }
    }

    /**
     * Обработка одного события
     */
    private function processEvent(array $event): void
    {
        $entityType = $event['entityType'] ?? null;
        $action = $event['action'] ?? null;
        $entityId = $event['entityId'] ?? null;

        // Извлекаем entityType и entityId из meta, если они не заданы напрямую
        if (isset($event['meta'])) {
            $meta = $event['meta'];
            if (isset($meta['type'])) {
                $entityType = $meta['type'];
            }
            if (isset($meta['href'])) {
                // Извлекаем ID из href (последняя часть после последнего /)
                $entityId = basename($meta['href']);
            }
        }

        Log::info('Обработка события МойСклад', [
            'entityType' => $entityType,
            'action' => $action,
            'entityId' => $entityId,
            'original_event' => $event,
        ]);

        // Обрабатываем только товары
        if ($entityType === 'product') {
            $this->processProductEvent($action, $entityId);
        }
    }

    /**
     * Обработка события товара
     */
    private function processProductEvent(string $action, string $entityId): void
    {
        Log::info('Обработка события товара', [
            'action' => $action,
            'entityId' => $entityId,
        ]);

        switch ($action) {
            case 'CREATE':
                $this->handleProductCreate($entityId);
                break;
            case 'UPDATE':
                $this->handleProductUpdate($entityId);
                break;
            case 'DELETE':
                $this->handleProductDelete($entityId);
                break;
            default:
                Log::warning('Неизвестное действие для товара', ['action' => $action]);
        }
    }

    /**
     * Обработка создания товара
     */
    private function handleProductCreate(string $entityId): void
    {
        Log::info('Создание товара', ['entityId' => $entityId]);

        // Получаем данные товара из МойСклад
        $productData = $this->getProductFromMoySklad($entityId);

        if ($productData) {
            // Синхронизируем товар
            $this->syncProduct($productData);

            // Синхронизируем сток товара
            $this->productService->syncProductStock($entityId);
        }
    }

    /**
     * Обработка обновления товара
     */
    private function handleProductUpdate(string $entityId): void
    {
        Log::info('Обновление товара', ['entityId' => $entityId]);

        // Получаем данные товара из МойСклад
        $productData = $this->getProductFromMoySklad($entityId);

        if ($productData) {
            // Синхронизируем товар
            $this->syncProduct($productData);

            // Синхронизируем сток товара
            $this->productService->syncProductStock($entityId);
        }
    }

    /**
     * Обработка удаления товара
     */
    private function handleProductDelete(string $entityId): void
    {
        Log::info('Удаление товара', ['entityId' => $entityId]);

        // Находим товар в нашей базе по external_id
        $product = \App\Models\Product::where('external_id', $entityId)->first();

        if ($product) {
            // Помечаем товар как неактивный или удаляем
            /** @var $product Product * */
            Log::info('Товар удален', ['product_id' => $product->id]);
            $product->delete();
        }
    }

    /**
     * Получение данных товара из МойСклад
     */
    private function getProductFromMoySklad(string $entityId): ?array
    {
        try {
            $response = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                ])
                ->get("https://api.moysklad.ru/api/remap/1.2/entity/product/{$entityId}");

            if ($response->successful()) {
                return $response->json();
            }
            $msErr = MoySkladApiHelper::formatErrorsFromResponse($response);
            Log::error("Ошибка получения товара из МойСклад: {$msErr}", [
                'entityId' => $entityId,
                'status' => $response->status(),
                'moysklad_error' => $msErr,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Исключение при получении товара из МойСклад', [
                'entityId' => $entityId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Синхронизация товара
     */
    private function syncProduct(array $productData): void
    {
        try {
            // Используем новый метод для синхронизации товара из вебхука
            $this->productService->syncProductFromWebhook($productData);

            Log::info('Товар успешно синхронизирован', ['entityId' => $productData['id']]);

        } catch (\Exception $e) {
            Log::error('Ошибка синхронизации товара', [
                'entityId' => $productData['id'] ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
