<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProductJob;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CommandRunnerController extends Controller
{
    /**
     * Генерация sitemap.xml
     */
    public function generateSitemap(): JsonResponse
    {
        try {
            Artisan::call('generate:sitemap');

            return response()->json([
                'success' => true,
                'message' => 'Sitemap успішно згенеровано у public/sitemap.xml',
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка генерації sitemap', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Помилка: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Перевірка доступності вебхука (TCP+TLS+HTTP статус)
     */
    public function webhookCheck(Request $request): JsonResponse
    {
        $url = (string) ($request->input('url') ?: request()->getSchemeAndHttpHost().'/webhook/moysklad');

        try {
            $start = microtime(true);
            $response = Http::withHeaders([
                'User-Agent' => 'WrapShop/CommandRunner',
            ])
                ->timeout(3)
                ->connectTimeout(1)
                ->retry(0)
                ->head($url);

            $ms = (int) ((microtime(true) - $start) * 1000);

            return response()->json([
                'success' => true,
                'message' => 'Перевірка виконана',
                'status' => $response->status(),
                'timeMs' => $ms,
                'url' => $url,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка підключення: '.$e->getMessage(),
                'url' => $url,
            ], 400);
        }
    }

    /**
     * Відправити тестовий вебхук з мінімальним JSON
     */
    public function webhookTest(Request $request): JsonResponse
    {
        $url = (string) ($request->input('url') ?: request()->getSchemeAndHttpHost().'/webhook/moysklad');
        $payload = $request->input('payload', [
            'event' => 'test',
            'source' => 'WrapShop',
            'timestamp' => now()->toISOString(),
        ]);

        try {
            $start = microtime(true);
            $response = Http::withHeaders([
                'User-Agent' => 'WrapShop/CommandRunner',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->timeout(3)
                ->connectTimeout(1)
                ->retry(0)
                ->post($url, $payload);

            $ms = (int) ((microtime(true) - $start) * 1000);

            return response()->json([
                'success' => $response->successful(),
                'message' => 'Тестовий вебхук відправлено',
                'status' => $response->status(),
                'timeMs' => $ms,
                'url' => $url,
                'body' => $response->body(),
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка відправки: '.$e->getMessage(),
                'url' => $url,
            ], 400);
        }
    }

    /**
     * Створити 3 вебхуки (CREATE/UPDATE/DELETE product) без SSH
     */
    public function webhookCreate(Request $request): JsonResponse
    {
        $url = (string) ($request->input('url') ?: request()->getSchemeAndHttpHost().'/webhook/moysklad');
        $token = (string) ($request->input('token') ?: env('MOYSKLAD_TOKEN', ''));
        $apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/webhook';

        if ($token === '') {
            return response()->json([
                'success' => false,
                'message' => 'Не вказано токен (MOYSKLAD_TOKEN)',
            ], 400);
        }

        // Убеждаемся, что URL полный и валидный
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            Log::error('Webhook URL validation failed', [
                'url' => $url,
                'is_valid' => filter_var($url, FILTER_VALIDATE_URL),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'URL не является корректным адресом: '.$url,
            ], 400);
        }

        Log::info('Creating webhooks with URL', ['url' => $url]);

        $toCreate = [
            ['action' => 'CREATE', 'entityType' => 'product'],
            ['action' => 'UPDATE', 'entityType' => 'product'],
            ['action' => 'DELETE', 'entityType' => 'product'],
        ];

        $results = [];

        try {
            foreach ($toCreate as $cfg) {
                $resp = Http::withHeaders([
                    'Authorization' => 'Bearer '.$token,
                    'Accept-Encoding' => 'gzip',
                    'Content-Type' => 'application/json',
                ])
                    ->timeout(5)
                    ->post($apiUrl, [
                        'url' => $url,
                        'action' => $cfg['action'],
                        'entityType' => $cfg['entityType'],
                    ]);

                $body = $resp->json();

                // Логируем ошибки от MoySklad
                if (! $resp->successful()) {
                    Log::error('MoySklad webhook creation failed', [
                        'action' => $cfg['action'],
                        'status' => $resp->status(),
                        'body' => $body,
                        'url' => $url,
                    ]);
                }

                $results[] = [
                    'action' => $cfg['action'],
                    'status' => $resp->status(),
                    'ok' => $resp->successful(),
                    'body' => $body,
                ];
            }

            $ok = collect($results)->every(fn ($r) => $r['ok'] === true);

            return response()->json([
                'success' => $ok,
                'message' => $ok ? 'Вебхуки створено' : 'Створено з помилками',
                'results' => $results,
                'url' => $url,
            ], $ok ? 200 : 207);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка створення: '.$e->getMessage(),
            ], 400);
        }
    }

    /**
     * Обновление товаров
     */
    public function updateProducts(Request $request): JsonResponse
    {
        try {
            $start = (int) ($request->input('start', 0));
            $end = (int) ($request->input('end', 1000));

            if ($start < 0 || $end <= $start) {
                return response()->json([
                    'success' => false,
                    'message' => 'Некоректні параметри діапазону',
                ], 400);
            }

            $productService = app(ProductService::class);
            $chunkSize = 500;
            $jobsCount = 0;

            for ($offset = $start; $offset < $end; $offset += $chunkSize) {
                $limit = min($chunkSize, $end - $offset);
                UpdateProductJob::dispatch($productService, $offset, $limit);
                $jobsCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Поставлено в чергу {$jobsCount} джоб(и) на оновлення товарів",
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка оновлення товарів', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Помилка: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Очистка кешей
     */
    public function clearCache(): JsonResponse
    {
        try {
            Artisan::call('optimize:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'Кеш успішно очищено',
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка очищення кешу', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Помилка: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Очистка неиспользуемых медиафайлов
     */
    public function cleanMedia(Request $request): JsonResponse
    {
        try {
            $dryRun = (bool) $request->input('dryRun', true);

            Artisan::call('media-library:clean', ['--dry-run' => $dryRun]);
            $output = Artisan::output();

            // Підрахунок кількості рядків/файлів у виводі
            $lines = preg_split('/\r?\n/', trim($output));
            $count = 0;
            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    $count++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => $dryRun
                    ? 'Тестовий запуск: буде видалено файлів (орієнтовно)'
                    : 'Неіснуючі медіафайли успішно видалено',
                'details' => null,
                'count' => $count,
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка очищення медіа', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Помилка: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Создать (или пересоздать) storage symlink без SSH
     */
    public function storageLink(Request $request): JsonResponse
    {
        try {
            $force = (bool) $request->input('force', false);

            // При необходимости удаляем существующую ссылку
            if ($force) {
                @unlink(public_path('storage'));
            }

            \Artisan::call('storage:link');
            $output = \Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Ссылка public/storage успешно '.($force ? 'пересоздана' : 'создана'),
                'details' => trim($output),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Ошибка storage:link', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Выполнить произвольную Artisan команду
     */
    public function runCustomCommand(Request $request): JsonResponse
    {
        try {
            $command = trim($request->input('command', ''));

            if (empty($command)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Команда не может быть пустой',
                ], 400);
            }

            // queue:work — довгоживучий процес, через веб призведе до таймауту
            if (preg_match('/^queue:work(?:\s|$)/', $command)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Команду queue:work не можна виконувати через веб (працює нескінченно). Запустіть на хості в консолі: php artisan queue:work',
                    'command' => $command,
                    'output' => null,
                ], 400);
            }

            // Синк цін/залишків — ставимо прапорець у cache; виконається при виклику scheduler (cron або URL без SSH)
            if (preg_match('/^products:sync-prices-and-stock(?:\s|$)/', $command)) {
                $chunk = 100;
                $limit = 0;
                if (preg_match('/--chunk=(\d+)/', $command, $m)) {
                    $chunk = max(1, min(500, (int) $m[1]));
                }
                if (preg_match('/--limit=(\d+)/', $command, $m)) {
                    $limit = max(0, (int) $m[1]);
                }
                Cache::put('sync_prices_stock_pending', ['chunk' => $chunk, 'limit' => $limit], 600);

                return response()->json([
                    'success' => true,
                    'message' => 'Вона виконається у фоні за кілька хвилин.',
                    'command' => $command,
                    'output' => null,
                ]);
            }

            // Лише category_id з МС — одразу Artisan (ланцюжок джоб у БД/Redis), без очікування cron schedule:run
            if (preg_match('/^products:sync-categories-from-moysklad(?:\s|$)/', $command)) {
                $chunk = (int) config('app.schedule_price_sync_batch', 100);
                $chunk = max(1, min(500, $chunk));
                $limit = 0;
                if (preg_match('/--chunk=(\d+)/', $command, $m)) {
                    $chunk = max(1, min(500, (int) $m[1]));
                }
                if (preg_match('/--limit=(\d+)/', $command, $m)) {
                    $limit = max(0, (int) $m[1]);
                }
                $output = $this->runProductCategoriesArtisanSync($chunk, $limit, 'custom-artisan', auth()->user()?->email);

                return response()->json([
                    'success' => true,
                    'message' => $limit === 0
                        ? 'Ланцюжок джоб поставлено в чергу (перевірте таблицю jobs при QUEUE_CONNECTION=database). Запустіть queue:work.'
                        : 'Команда синхронізації з лімітом виконана (див. output).',
                    'command' => $command,
                    'output' => $output !== '' ? $output : null,
                ]);
            }

            Log::info('Executing custom Artisan command', [
                'command' => $command,
                'user' => auth()->user()?->email ?? 'unknown',
            ]);

            // Выполняем команду
            Artisan::call($command);
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Команда выполнена успешно',
                'command' => $command,
                'output' => trim($output),
            ]);

        } catch (\Throwable $e) {
            Log::error('Ошибка выполнения пользовательской команды', [
                'command' => $request->input('command'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка выполнения команды: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Синхронізація цін та залишків товарів з МойСклад — ставиться в чергу, щоб уникнути 504 Gateway Timeout.
     */
    public function syncPricesAndStock(Request $request): JsonResponse
    {
        try {
            $chunk = (int) $request->input('chunk', 100);
            $chunk = max(1, min(500, $chunk));
            $limit = (int) $request->input('limit', 0);

            Cache::put('sync_prices_stock_pending', ['chunk' => $chunk, 'limit' => $limit], 600);

            return response()->json([
                'success' => true,
                'message' => 'Вона виконається у фоні за кілька хвилин.',
                'output' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка постановки синхронізації цін/залишків у чергу', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Помилка: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Оновлення лише category_id з атрибута «Категорія сайту» в МойСклад (без повного синку картки).
     * Виконується одразу через Artisan (без очікування cron). limit=0 — Bus::chain джоб; limit>0 — синхронний прохід у цьому запиті.
     */
    public function syncProductCategories(Request $request): JsonResponse
    {
        try {
            $defaultChunk = (int) config('app.schedule_price_sync_batch', 100);
            $chunk = (int) $request->input('chunk', $defaultChunk);
            $chunk = max(1, min(500, $chunk));
            $limit = (int) $request->input('limit', 0);

            $output = $this->runProductCategoriesArtisanSync($chunk, $limit, 'command-runner-ui', auth()->user()?->email);

            $queueConn = (string) config('queue.default', 'sync');
            $msg = $limit === 0
                ? 'Ланцюжок джоб поставлено в чергу. Перевірте таблицю jobs при QUEUE_CONNECTION=database і запустіть queue:work. API МС: MoySkladRemapHttp, MOY_SKLAD_REMAP_DELAY_MS.'
                : 'Синхронізація з лімітом виконана в цьому запиті (великий limit може таймаутитись — краще з консолі).';
            if ($limit === 0 && $queueConn === 'sync') {
                $msg .= ' Зараз QUEUE_CONNECTION=sync — джоби виконуються в цьому ж запиті, таблиця jobs не використовується.';
            }

            return response()->json([
                'success' => true,
                'message' => $msg,
                'output' => $output !== '' ? $output : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка постановки синхронізації категорій товарів', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Помилка: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * limit=0 — тільки dispatch ланцюжка джоб (швидко); limit>0 — повний прохід artisan у поточному процесі.
     *
     * @param  string  $via  джерело виклику (для логів)
     */
    private function runProductCategoriesArtisanSync(int $chunk, int $limit, string $via = 'unknown', ?string $userEmail = null): string
    {
        $chunk = max(1, min(500, $chunk));
        $limit = max(0, $limit);

        Log::info('CommandRunner: старт artisan синхронізації категорій товарів (МС)', [
            'via' => $via,
            'user' => $userEmail ?? auth()->user()?->email ?? 'guest',
            'chunk' => $chunk,
            'limit' => $limit,
            'queue_connection' => config('queue.default'),
        ]);

        if ($limit === 0) {
            Artisan::call('products:dispatch-category-sync-jobs', [
                '--batch' => $chunk,
            ]);
        } else {
            Artisan::call('products:sync-categories-from-moysklad', [
                '--chunk' => $chunk,
                '--limit' => $limit,
            ]);
        }

        $out = trim(Artisan::output());

        Log::info('CommandRunner: artisan синхронізації категорій завершено', [
            'via' => $via,
            'chunk' => $chunk,
            'limit' => $limit,
            'artisan_output' => $out !== '' ? $out : null,
        ]);

        return $out;
    }
}
