<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Psy\Util\Str;
use function Doctrine\DBAL\Query\orderBy;

class NovaPoshtaService
{
    public string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.novaposhta.api_key');
    }

    /**
     * Получить список городов по названию
     */
    public function getCities(string $cityName = null)
    {
        $methodProperties = [];
        if ($cityName) {
            $methodProperties['FindByString'] = $cityName;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://api.novaposhta.ua/v2.0/json/', [
            'apiKey' => $this->apiKey,
            'modelName' => 'Address',
            'calledMethod' => 'getCities',
            'methodProperties' => $methodProperties  // Передаем метод с фильтром
        ]);

        $data = $response->json();
        $locale = app()->getLocale();

        if ($data['success']) {
            $cities = collect($data['data'])->map(function ($city) use($locale) {
                return [
                    'name' => $locale === 'ru' ? $city['DescriptionRu'] : $city['Description'],
                    'region' => $locale === 'ru' ? $city['AreaDescriptionRu'] : $city['AreaDescription'],
                    'ref' => $city['Ref'],
                ];
            });

        } else {
            dd('Ошибка: ', $data['errors']);
        }

        return $cities ?? [];
    }

    /**
     * Получить отделения по городу
     */
    public function getWarehouses(string $cityRef)
    {
        try {
            $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
                'apiKey'           => $this->apiKey,
                'modelName'        => 'Address',
                'calledMethod'     => 'getWarehouses',
                'methodProperties' => [
                    'CityRef' => $cityRef,
                ]
            ]);

            // Проверяем успешность запроса
            if ($response->successful()) {
                $data = $response->json();

                $locale = app()->getLocale();
                // Если данные есть, продолжаем маппинг
                if (!empty($data['data'])) {
                    $warehouses = collect($data['data'])->filter(function ($warehouse) {
                        return $warehouse['CategoryOfWarehouse'] === 'Branch' || $warehouse['CategoryOfWarehouse'] === 'Store';
                    })->map(function ($warehouse) use($locale) {
                        return [
                            'id' => $warehouse['Ref'], // уникальный идентификатор склада
                            'name' => $locale === 'ru' ? $warehouse['DescriptionRu'] : $warehouse['Description'],
                            'address' => $warehouse['ShortAddress'], // короткий адрес
                            'city' => $warehouse['CityDescription'], // город
                            'region' =>  $locale === 'ru' ?  $warehouse['SettlementAreaDescriptionRu']: $warehouse['SettlementAreaDescription'], // область
                        ];
                    });

                    return $warehouses->toArray(); // Возвращаем результат
                } else {
                    return [];
                }
            } else {
                // Логирование ошибки
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Получить почтоматы по городу
     */
    public function getPostMachines(string $cityRef, int $retryCount = 0)
    {
        try {
            // Добавляем задержку между запросами к API Nova Poshta
            static $lastRequestTime = 0;
            $currentTime = microtime(true);
            $timeSinceLastRequest = $currentTime - $lastRequestTime;
            
            if ($timeSinceLastRequest < 0.5) {
                $sleepTime = 0.5 - $timeSinceLastRequest;
                \Log::info('NovaPoshta API - задержка между запросами', [
                    'sleep_time' => $sleepTime,
                    'time_since_last_request' => $timeSinceLastRequest
                ]);
                usleep($sleepTime * 1000000); // Конвертируем в микросекунды
            }
            
            $lastRequestTime = microtime(true);
            
            \Log::info('NovaPoshta API - запрос почтоматов', [
                'cityRef' => $cityRef,
                'apiKey' => substr($this->apiKey, 0, 10) . '...',
                'apiKey_length' => strlen($this->apiKey),
                'apiKey_empty' => empty($this->apiKey)
            ]);
            
            $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
                'apiKey'           => $this->apiKey,
                'modelName'        => 'Address',
                'calledMethod'     => 'getWarehouses',
                'methodProperties' => [
                    'CityRef' => $cityRef,
                ]
            ]);

            \Log::info('NovaPoshta API - ответ почтоматов', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_length' => strlen($response->body()),
                'body_preview' => substr($response->body(), 0, 500)
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $locale = app()->getLocale();

                \Log::info('NovaPoshta API - данные почтоматов', [
                    'has_data' => !empty($data['data']),
                    'data_count' => !empty($data['data']) ? count($data['data']) : 0,
                    'locale' => $locale,
                    'response_structure' => array_keys($data),
                    'success' => $data['success'] ?? 'not_set',
                    'errors' => $data['errors'] ?? 'not_set'
                ]);

                // Проверяем на ошибку "Too many requests"
                if (isset($data['success']) && $data['success'] === false && 
                    isset($data['errors']) && in_array('To many requests', $data['errors'])) {
                    
                    if ($retryCount < 3) {
                        \Log::warning('NovaPoshta API - превышен лимит запросов, повторная попытка', [
                            'cityRef' => $cityRef,
                            'retry_count' => $retryCount + 1,
                            'max_retries' => 3,
                            'errors' => $data['errors'],
                            'info' => $data['info'] ?? [],
                            'wait_seconds' => 1
                        ]);
                        
                        // Ждем 1 секунду и повторяем запрос
                        sleep(1);
                        return $this->getPostMachines($cityRef, $retryCount + 1);
                    } else {
                        \Log::error('NovaPoshta API - превышен лимит запросов, исчерпаны попытки', [
                            'cityRef' => $cityRef,
                            'retry_count' => $retryCount,
                            'errors' => $data['errors']
                        ]);
                        return [];
                    }
                }

                if (!empty($data['data'])) {
                    $allWarehouses = collect($data['data']);
                    
                    // Логируем все категории складов для отладки
                    $categories = $allWarehouses->pluck('CategoryOfWarehouse')->unique()->values()->toArray();
                    \Log::info('NovaPoshta API - категории складов', [
                        'categories' => $categories,
                        'total_warehouses' => $allWarehouses->count()
                    ]);
                    
                    $postomatWarehouses = $allWarehouses->filter(function ($warehouse) {
                        return $warehouse['CategoryOfWarehouse'] === 'Postomat';
                    });
                    
                    \Log::info('NovaPoshta API - фильтрация почтоматов', [
                        'total_warehouses' => $allWarehouses->count(),
                        'postomat_warehouses' => $postomatWarehouses->count(),
                        'first_warehouse' => $allWarehouses->first(),
                        'postomat_categories' => $postomatWarehouses->pluck('CategoryOfWarehouse')->unique()->values()->toArray()
                    ]);
                    
                    $postMachines = $postomatWarehouses->map(function ($warehouse) use ($locale) {
                        return [
                            'id'      => $warehouse['Ref'],
                            'name'    => $locale === 'ru' ? $warehouse['DescriptionRu'] : $warehouse['Description'],
                            'address' => $warehouse['ShortAddress'],
                            'city'    => $warehouse['CityDescription'],
                            'region'  => $locale === 'ru' ? $warehouse['SettlementAreaDescriptionRu'] : $warehouse['SettlementAreaDescription'],
                        ];
                    });

                    $result = $postMachines->toArray();
                    
                    // Преобразуем в объект с числовыми ключами для совместимости с JavaScript
                    $resultObject = [];
                    foreach ($result as $index => $item) {
                        $resultObject[$index] = $item;
                    }
                    
                    \Log::info('NovaPoshta API - результат почтоматов', [
                        'result_count' => count($resultObject),
                        'is_array' => is_array($resultObject),
                        'is_object' => is_object($resultObject),
                        'first_item' => !empty($resultObject) ? reset($resultObject) : null
                    ]);
                    
                    return $resultObject;
                } else {
                    \Log::warning('NovaPoshta API - пустые данные', [
                        'cityRef' => $cityRef,
                        'response_data' => $data,
                        'has_data_key' => isset($data['data']),
                        'data_empty' => empty($data['data'])
                    ]);
                    return [];
                }
            } else {
                \Log::error('NovaPoshta API - ошибка ответа', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }
        } catch (\Exception $e) {
            \Log::error('NovaPoshta API - исключение', [
                'message' => $e->getMessage(),
                'cityRef' => $cityRef
            ]);
            return [];
        }
    }
}
