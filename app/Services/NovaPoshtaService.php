<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
     * Отделения по городу.
     *
     * @param  bool  $cargoOnly  Плівка від 1 м.п. — лише вантажні відділення НП (ліміт у довіднику 200/1100 кг тощо).
     */
    public function getWarehouses(?string $cityRef, bool $cargoOnly = false): array
    {
        if ($cityRef === null || $cityRef === '') {
            return [];
        }

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
                    $rows = collect($data['data'])->filter(function ($warehouse) {
                        return ($warehouse['CategoryOfWarehouse'] ?? '') === 'Branch'
                            || ($warehouse['CategoryOfWarehouse'] ?? '') === 'Store';
                    });

                    if ($cargoOnly) {
                        $filtered = $rows->filter(fn (array $w) => $this->isCargoWarehouse($w));
                        if ($filtered->isEmpty() && $rows->isNotEmpty()) {
                            \Log::warning('Nova Poshta: cargo_only відфільтрував усі відділення, повертаємо повний список Branch/Store', [
                                'cityRef' => $cityRef,
                                'before' => $rows->count(),
                            ]);
                            $filtered = $rows;
                        }
                        $rows = $filtered;
                    }

                    return $rows->map(function ($warehouse) use ($locale) {
                        $baseName = $locale === 'ru'
                            ? (string) ($warehouse['DescriptionRu'] ?? '')
                            : (string) ($warehouse['Description'] ?? '');

                        return [
                            'id' => $warehouse['Ref'], // уникальный идентификатор склада
                            'name' => $this->warehouseDisplayName($warehouse, $baseName, $locale),
                            'address' => $warehouse['ShortAddress'], // короткий адрес
                            'city' => $warehouse['CityDescription'], // город
                            'region' =>  $locale === 'ru' ?  $warehouse['SettlementAreaDescriptionRu']: $warehouse['SettlementAreaDescription'], // область
                        ];
                    })->values()->all();
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
     * Назва для UI: у API часто короткий Description, у Google/на вивісках — «Вантажне відділення №1 (до 1100 кг)».
     * Доповнюємо з PlaceMaxWeightAllowed та позначкою вантажного класу, якщо цього немає в тексті з НП.
     */
    private function warehouseDisplayName(array $w, string $baseName, string $locale): string
    {
        $name = $baseName;
        $lower = mb_strtolower($name);

        $pw = $this->parseNpScalar($w['PlaceMaxWeightAllowed'] ?? null);

        $hasKgInText = (bool) preg_match('/\d{2,4}\s*кг/u', $name);
        $hasCargoWords = str_contains($lower, 'вантаж')
            || str_contains($lower, 'грузов')
            || str_contains($lower, 'груз')
            || str_contains($lower, 'cargo');
        $hasCargoLimitInText = $hasKgInText
            || str_contains($lower, '200')
            || str_contains($lower, '1100')
            || str_contains($lower, '1000');

        if ($pw !== null && $pw >= 45 && ! $hasKgInText) {
            $kg = (int) round($pw);
            $name .= match ($locale) {
                'ru' => " (до {$kg} кг)",
                'en' => " (up to {$kg} kg)",
                default => " (до {$kg} кг)",
            };
        }

        if ($this->isCargoWarehouse($w) && ! $hasCargoWords && ! $hasCargoLimitInText) {
            if ($locale === 'ru' && preg_match('/^Отделение\s+/u', $name)) {
                $name = preg_replace('/^Отделение\s+/u', 'Грузовое отделение ', $name, 1);
            } elseif ($locale !== 'ru' && preg_match('/^Відділення\s+/u', $name)) {
                $name = preg_replace('/^Відділення\s+/u', 'Вантажне відділення ', $name, 1);
            }
        }

        return $name;
    }

    /**
     * Вантажне відділення для рулонної плівки: Place → Total (лише якщо place немає) → текст опису.
     */
    private function isCargoWarehouse(array $w): bool
    {
        $pw = $this->parseNpScalar($w['PlaceMaxWeightAllowed'] ?? null);

        if ($pw !== null) {
            return $pw >= 200;
        }

        $tw = $this->parseNpScalar($w['TotalMaxWeightAllowed'] ?? null);

        if ($tw !== null) {
            return $tw >= 200;
        }

        $text = mb_strtolower(
            (string) ($w['Description'] ?? '').' '.(string) ($w['DescriptionRu'] ?? ''),
            'UTF-8'
        );

        return str_contains($text, '200')
            || str_contains($text, '1100')
            || str_contains($text, 'вантаж')
            || str_contains($text, 'груз');
    }

    private function parseNpScalar(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $normalized = str_replace([' ', ','], ['', '.'], (string) $value);
        if (! is_numeric($normalized)) {
            return null;
        }
        $n = (float) $normalized;

        return is_finite($n) ? $n : null;
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
                usleep($sleepTime * 1000000); // Конвертируем в микросекунды
            }
            
            $lastRequestTime = microtime(true);
            
            $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
                'apiKey'           => $this->apiKey,
                'modelName'        => 'Address',
                'calledMethod'     => 'getWarehouses',
                'methodProperties' => [
                    'CityRef' => $cityRef,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $locale = app()->getLocale();

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
                    
                    $postomatWarehouses = $allWarehouses->filter(function ($warehouse) {
                        return $warehouse['CategoryOfWarehouse'] === 'Postomat';
                    });
                    
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
