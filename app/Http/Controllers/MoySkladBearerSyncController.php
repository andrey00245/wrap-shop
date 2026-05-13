<?php

namespace App\Http\Controllers;

use App\Helpers\MoySkladApiHelper;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Тестовий роут: Bearer до МойСклад (MOY_SKLAD_TOKEN з конфіга).
 *
 * - `GET ?ping=1` — лише перевірка з’єднання (GET entity/product?limit=1), без товару в БД.
 * - Без query: синк цін/залишку для товару з `config my_store.bearer_test_product_id` (.env MOY_SKLAD_BEARER_TEST_PRODUCT_ID)
 *   або `?product_id=` / `?external_id=`.
 *
 * Доступ: секрет з `config('app.moysklad_bearer_sync_secret')` або SCHEDULER_TOKEN — у query `?token=` або заголовок `X-Moysklad-Sync-Token`.
 * Якщо обидва секрети порожні — без перевірки (як /run-scheduler).
 */
class MoySkladBearerSyncController extends Controller
{
    public function __invoke(Request $request, ProductService $productService): JsonResponse
    {
        $this->assertValidAccessToken($request);

        if ($request->boolean('ping')) {
            return $this->pingBearer();
        }

        dd(1);
        $productId = $request->query('product_id');
        $externalId = $request->query('external_id');
        $fromConfig = config('app.my_store.bearer_test_product_id');

        if ($productId !== null && $productId !== '') {
            $product = Product::query()->find($productId);
        } elseif ($externalId !== null && $externalId !== '') {
            $product = Product::query()->where('product_id', 333)->first();
            dd($product);
        } elseif ($fromConfig !== null && $fromConfig !== '') {
            $product = Product::query()->find((int) $fromConfig);
        } else {
            return response()->json([
                'ok' => false,
                'message' => 'Задайте MOY_SKLAD_BEARER_TEST_PRODUCT_ID у .env або передайте product_id / external_id',
            ], 422);
        }

        if (! $product) {
            return response()->json(['ok' => false, 'message' => 'Товар не знайдено'], 404);
        }

        $result = $productService->syncProductPricesAndStockWithBearer($product);

        return response()->json($result, ($result['ok'] ?? false) ? 200 : 422);
    }

    /**
     * Перевірка з’єднання з API МойСклад по MOY_SKLAD_TOKEN (з .env / config).
     */
    private function pingBearer(): JsonResponse
    {
        $token = trim((string) config('app.my_store.token'));
        if ($token === '') {
            return response()->json([
                'ok' => false,
                'message' => 'MOY_SKLAD_TOKEN не заданий у .env (config app.my_store.token)',
            ], 422);
        }

        $http = Http::withHeaders(['Accept-Encoding' => 'gzip'])->withToken($token);
        $response = $http->get('https://api.moysklad.ru/api/remap/1.2/entity/product?limit=1');

        dd($response);
        if (! $response->successful()) {
            return response()->json([
                'ok' => false,
                'message' => MoySkladApiHelper::formatErrorsFromResponse($response),
                'status' => $response->status(),
            ], 422);
        }

        $json = $response->json();

        return response()->json([
            'ok' => true,
            'message' => 'З’єднання з МойСклад (Bearer) OK',
            'meta' => $json['meta'] ?? null,
            'rows_count' => isset($json['rows']) && is_array($json['rows']) ? count($json['rows']) : 0,
        ]);
    }

    private function assertValidAccessToken(Request $request): void
    {
        $allowed = array_values(array_filter([
            trim((string) config('app.moysklad_bearer_sync_secret', '')),
            trim((string) env('SCHEDULER_TOKEN', '')),
        ], fn (string $t): bool => $t !== ''));

        if ($allowed === []) {
            return;
        }

    }
}
