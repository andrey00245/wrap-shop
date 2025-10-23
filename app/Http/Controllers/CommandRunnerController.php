<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProductJob;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
                'message' => 'Sitemap успішно згенеровано у public/sitemap.xml'
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка генерації sitemap', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Помилка: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Перевірка доступності вебхука (TCP+TLS+HTTP статус)
     */
    public function webhookCheck(Request $request): JsonResponse
    {
        $url = (string) ($request->input('url') ?: request()->getSchemeAndHttpHost() . '/webhook/moysklad');

        try {
            $start = microtime(true);
            $response = Http::withHeaders([
                    'User-Agent' => 'WrapShop/CommandRunner'
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
                'message' => 'Помилка підключення: ' . $e->getMessage(),
                'url' => $url,
            ], 400);
        }
    }

    /**
     * Відправити тестовий вебхук з мінімальним JSON
     */
    public function webhookTest(Request $request): JsonResponse
    {
        $url = (string) ($request->input('url') ?: request()->getSchemeAndHttpHost() . '/webhook/moysklad');
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
                    'Accept' => 'application/json'
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
                'message' => 'Помилка відправки: ' . $e->getMessage(),
                'url' => $url,
            ], 400);
        }
    }

    /**
     * Створити 3 вебхуки (CREATE/UPDATE/DELETE product) без SSH
     */
    public function webhookCreate(Request $request): JsonResponse
    {
        $url = (string) ($request->input('url') ?: request()->getSchemeAndHttpHost() . '/webhook/moysklad');
        $token = (string) ($request->input('token') ?: env('MOYSKLAD_TOKEN', ''));
        $apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/webhook';

        if ($token === '') {
            return response()->json([
                'success' => false,
                'message' => 'Не вказано токен (MOYSKLAD_TOKEN)',
            ], 400);
        }

        // Убеждаемся, что URL полный и валидный
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            Log::error('Webhook URL validation failed', [
                'url' => $url,
                'is_valid' => filter_var($url, FILTER_VALIDATE_URL)
            ]);
            return response()->json([
                'success' => false,
                'message' => 'URL не является корректным адресом: ' . $url,
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
                        'Authorization' => 'Bearer ' . $token,
                        'Accept-Encoding' => 'gzip',
                        'Content-Type' => 'application/json'
                    ])
                    ->timeout(5)
                    ->post($apiUrl, [
                        'url' => $url,
                        'action' => $cfg['action'],
                        'entityType' => $cfg['entityType'],
                    ]);

                $body = $resp->json();
                
                // Логируем ошибки от MoySklad
                if (!$resp->successful()) {
                    Log::error('MoySklad webhook creation failed', [
                        'action' => $cfg['action'],
                        'status' => $resp->status(),
                        'body' => $body,
                        'url' => $url
                    ]);
                }

                $results[] = [
                    'action' => $cfg['action'],
                    'status' => $resp->status(),
                    'ok' => $resp->successful(),
                    'body' => $body,
                ];
            }

            $ok = collect($results)->every(fn($r) => $r['ok'] === true);

            return response()->json([
                'success' => $ok,
                'message' => $ok ? 'Вебхуки створено' : 'Створено з помилками',
                'results' => $results,
                'url' => $url,
            ], $ok ? 200 : 207);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка створення: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Обновление товаров
     */
    public function updateProducts(Request $request): JsonResponse
    {
        try {
            $start = (int)($request->input('start', 0));
            $end = (int)($request->input('end', 1000));

            if ($start < 0 || $end <= $start) {
                return response()->json([
                    'success' => false,
                    'message' => 'Некоректні параметри діапазону'
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
                'message' => "Поставлено в чергу {$jobsCount} джоб(и) на оновлення товарів"
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка оновлення товарів', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Помилка: ' . $e->getMessage()
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
                'message' => 'Кеш успішно очищено'
            ]);
        } catch (\Throwable $e) {
            Log::error('Помилка очищення кешу', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Помилка: ' . $e->getMessage()
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

            Artisan::call('media-library:clean', [ '--dry-run' => $dryRun ]);
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
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Помилка: ' . $e->getMessage()
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
                'trace' => $e->getTraceAsString()
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
                    'message' => 'Команда не может быть пустой'
                ], 400);
            }

            // Безопасность: разрешаем только определенные команды
            $allowedCommands = [
                'cache:clear',
                'config:clear',
                'view:clear',
                'route:clear',
                'optimize:clear',
                'storage:link',
                'migrate',
                'migrate:status',
                'db:seed',
                'queue:work',
                'queue:restart',
                'queue:failed',
                'tinker',
                'make:controller',
                'make:model',
                'make:migration',
                'make:seeder',
                'make:command',
                'media:generate-sync',
                'media:clean-filenames',
                'products:recreate-media',
                'products:list',
                'generate:sitemap',
                'media-library:clean',
                'media:analyze',
                'media:analyze-unused',
                'media:cleanup-unused',
                'media:cleanup-old-conversions',
            ];

            // Извлекаем команду без аргументов для проверки
            $baseCommand = explode(' ', $command)[0];
            
            if (!in_array($baseCommand, $allowedCommands)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Команда "' . $baseCommand . '" не разрешена для выполнения'
                ], 403);
            }

            Log::info('Executing custom Artisan command', [
                'command' => $command,
                'user' => auth()->user()?->email ?? 'unknown'
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
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка выполнения команды: ' . $e->getMessage(),
            ], 500);
        }
    }
}