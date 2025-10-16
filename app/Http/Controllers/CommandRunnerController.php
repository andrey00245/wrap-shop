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
        $url = (string) ($request->input('url') ?: url('/webhook/moysklad'));

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
        $url = (string) ($request->input('url') ?: url('/webhook/moysklad'));
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
        $url = (string) ($request->input('url') ?: url('/webhook/moysklad'));
        $token = (string) ($request->input('token') ?: env('MOYSKLAD_TOKEN', ''));
        $apiUrl = 'https://api.moysklad.ru/api/remap/1.2/entity/webhook';

        if ($token === '') {
            return response()->json([
                'success' => false,
                'message' => 'Не вказано токен (MOYSKLAD_TOKEN)',
            ], 400);
        }

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

                $results[] = [
                    'action' => $cfg['action'],
                    'status' => $resp->status(),
                    'ok' => $resp->successful(),
                    'body' => $resp->json(),
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
     * Генерация конверсий медиа файлов
     */
    public function generateConversions(Request $request): JsonResponse
    {
        try {
            $force = (bool) $request->input('force', false);

            Artisan::call('media:generate-conversions', [ '--force' => $force ]);
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => $output
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка генерации конверсий', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Анализ медиа файлов
     */
    public function analyzeMedia(Request $request): JsonResponse
    {
        try {
            Artisan::call('media:analyze');
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Анализ медиа завершен',
                'details' => $output,
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка анализа медиа', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Анализ неиспользуемых медиа файлов
     */
    public function analyzeUnusedMedia(Request $request): JsonResponse
    {
        try {
            Artisan::call('media:analyze-unused', ['--dry-run' => true]);
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => $output
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка анализа неиспользуемых медиа', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Очистка неиспользуемых медиа файлов
     */
    public function cleanupUnusedMedia(Request $request): JsonResponse
    {
        try {
            Artisan::call('media:cleanup-unused');
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => $output
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка очистки неиспользуемых медиа', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Очистка старых конверсий
     */
    public function cleanupOldConversions(Request $request): JsonResponse
    {
        try {
            $dryRun = $request->input('dry_run', true);
            $dryRunFlag = $dryRun ? '--dry-run' : '';
            
            Artisan::call('media:cleanup-old-conversions', [
                '--dry-run' => $dryRun
            ]);
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => $output
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка очистки старых конверсий', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Показать хвост логов (laravel.log) для быстрого контроля
     */
    public function tailLogs(Request $request): JsonResponse
    {
        try {
            $lines = (int) $request->input('lines', 200);
            $logFile = storage_path('logs/laravel.log');

            if (!file_exists($logFile)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Лог-файл отсутствует',
                    'data'    => ''
                ]);
            }

            // Читаем последние N строк эффективно
            $fp = fopen($logFile, 'r');
            $buffer = '';
            $chunkSize = 4096;
            $pos = -1;
            $lineCount = 0;

            fseek($fp, 0, SEEK_END);
            $fileSize = ftell($fp);

            while ($lineCount <= $lines && $fileSize > 0) {
                $seek = max($fileSize - $chunkSize, 0);
                $read = $fileSize - $seek;
                fseek($fp, $seek);
                $data = fread($fp, $read);
                $buffer = $data . $buffer;
                $fileSize = $seek;
                $lineCount = substr_count($buffer, "\n");
                if ($seek === 0) break;
            }
            fclose($fp);

            $linesArr = explode("\n", trim($buffer));
            $tail = implode("\n", array_slice($linesArr, -$lines));

            return response()->json([
                'success' => true,
                'data'    => $tail,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка чтения логов: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Очистить логи приложения (storage/logs/*.log)
     */
    public function clearLogs(): JsonResponse
    {
        try {
            $dir = storage_path('logs');
            $removed = 0;
            foreach (glob($dir.'/*.log') as $file) {
                @unlink($file);
                $removed++;
            }
            return response()->json([
                'success' => true,
                'message' => "Удалено лог-файлов: {$removed}",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка очистки логов: '.$e->getMessage()
            ], 500);
        }
    }
 
    /**
     * Создать конверсии для кастомных блоков
     */
    public function generateCustomBlockConversions(Request $request): JsonResponse
    {
        try {
            Artisan::call('media:generate-sync', [
                '--collection' => 'main',
                '--force' => (bool) $request->input('force', false),
                '--only-missing' => true,
            ]);
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Конверсии для кастомных блоков созданы',
                'details' => $output,
            ]);
        } catch (\Throwable $e) {
            Log::error('Ошибка создания конверсий для кастомных блоков', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage(),
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
}

