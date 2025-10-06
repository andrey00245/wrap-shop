<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProductJob;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
    public function cleanMedia(): JsonResponse
    {
        try {
            Artisan::call('media-library:clean');
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Неіснуючі медіафайли успішно видалено',
                'details' => trim($output)
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
}

