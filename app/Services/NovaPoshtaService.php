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
                        return $warehouse['CategoryOfWarehouse'] === 'Branch';
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
            // Логирование исключения
            return [];
        }
    }


}
