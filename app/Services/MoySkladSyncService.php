<?php

namespace App\Services;

use App\Http\Enums\DeliveryTypeEnum;
use App\Http\Enums\PaymentTypeEnum;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use MoySklad\Entities\Organization;
use MoySklad\MoySklad;
use MoySklad\Entities\Counterparty;
use ReflectionClass;

class MoySkladSyncService
{
    /**
     * @throws \MoySklad\Exceptions\IncompleteCreationFieldsException
     * @throws \Throwable
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \MoySklad\Exceptions\EntityCantBeMutatedException
     */
    public static function sendOrder(Order $order): void
    {
        $sklad = MoySklad::getInstance(
            config('app.my_store.username'),
            config('app.my_store.password')
        );

        $organizationsList = Organization::query($sklad)->getList();
        $organization = $organizationsList[0] ?? null;

        if (!$organization) {
            throw new \Exception('Организация не найдена');
        } else {
            $organizationMeta = $organization->fields->meta;

            $organization = [
                'meta' => [
                    'href'      => $organizationMeta->href,
                    'type'      => $organizationMeta->type,
                    'mediaType' => $organizationMeta->mediaType,
                ]
            ];
        }

        $clientName = trim(((string) ($order->first_name ?? '')) . ' ' . ((string) ($order->last_name ?? '')));
        $clientEmail = trim((string) ($order->email ?? ''));
        $cleanPhone = self::sanitizePhoneNumber($order->phone);

        $existing = self::findCounterpartyFirstRow($clientEmail, $cleanPhone);

        if ($existing !== null) {
            $counterpartyMeta = $existing['meta'];

            if (!isset($counterpartyMeta['href'])) {
                throw new \Exception('Некорректный meta в ответе контрагента');
            }

            $counterparty = new Counterparty($sklad);
            $counterparty->meta = [
                'type'      => $counterpartyMeta['type'] ?? 'counterparty',
                'href'      => $counterpartyMeta['href'],
                "mediaType" => "application/json"
            ];

            $attributes = [
                [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3c75f405-660c-11f0-0a80-03cb002a009d", // phone
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $cleanPhone
                ],
                [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/ee963740-660b-11f0-0a80-0d890028be5f", //FIO
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $clientName
                ]
            ];

            if ($order->novaposhta_warehouse_ref && $order->shipping_address) {
                $npValue = self::resolveNpCustomEntityValue(
                    (string) $order->novaposhta_warehouse_ref,
                    self::normalizeNpCounterpartyName((string) $order->shipping_address)
                );
                $attributes[] = [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3999ff42-6610-11f0-0a80-0462002ad32f",
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $npValue
                ];
            }

            $updatePayload = ['attributes' => $attributes];
            if ($clientEmail !== '') {
                $updatePayload['email'] = $clientEmail;
            }

            $updateResponse = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password'))
                    ->withHeaders([
                        'Accept-Encoding' => 'gzip',
                    ])->put($counterpartyMeta['href'], $updatePayload);

            if ($updateResponse->failed()) {
                Log::error('Не удалось обновить атрибуты контрагента', [
                    'response' => $updateResponse->json()
                ]);
            }

        } else {
            $counterparty = new Counterparty($sklad);
            $counterparty->name = $clientName !== '' ? $clientName : $cleanPhone;
            if ($clientEmail !== '') {
                $counterparty->email = $clientEmail;
            }
            $counterparty->phone = $cleanPhone;

            $attributes = [
                [
                    "meta"  => [
                        "href"      => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3c75f405-660c-11f0-0a80-03cb002a009d", // Phone
                        "type"      => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $cleanPhone
                ],
               [
                   "meta" => [
                       "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/ee963740-660b-11f0-0a80-0d890028be5f", //FIO
                       "type" => "attributemetadata",
                       "mediaType" => "application/json"
                   ],
                   "value" => $clientName
               ]
            ];

            if ($order->novaposhta_warehouse_ref && $order->shipping_address) {
                $npValue = self::resolveNpCustomEntityValue(
                    (string) $order->novaposhta_warehouse_ref,
                    self::normalizeNpCounterpartyName((string) $order->shipping_address)
                );
                $attributes[] = [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3999ff42-6610-11f0-0a80-0462002ad32f",
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $npValue
                ];
            }

            $counterparty->attributes = $attributes;
            $counterparty = $counterparty->create();

            // Получаем meta через рефлексию (как в твоём коде)
            $counterpartyMeta = self::getEntityMeta($counterparty);
            $counterparty->meta = [
                'type'      => $counterpartyMeta['type'] ?? 'counterparty',
                'href'      => $counterpartyMeta['href'],
                'mediaType' => 'application/json',
            ];
        }

        // Формируем позиции заказа — вытаскиваем продукты по коду через запрос и собираем массив для заказа
        $positions = [];
        foreach ($order->products as $orderProduct) {

            $productResponse = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                ])
                ->get('https://api.moysklad.ru/api/remap/1.2/entity/product', [
                    'filter' => 'code=' . $orderProduct->code,
                ]);

            $productData = $productResponse->json('rows')[0] ?? null;

            if (!$productData) {
                Log::warning("Не найден товар с кодом {$orderProduct->code}");
                continue;
            }

            // Цена должна быть в долларах (USD) и в минимальных единицах (центах)
            $unitPriceUah = $orderProduct->pivot->price
                ? (float) $orderProduct->pivot->price
                : (float) $orderProduct->getUnitPriceForQuantity((int)$orderProduct->pivot->quantity);
            $unitPriceUsd = (float) $orderProduct->getPriceByDollars($unitPriceUah);

            $positions[] = [
                'quantity'   => (float)$orderProduct->pivot->quantity,
                'price'      => (int) round($unitPriceUsd * 100),
                'assortment' => [
                    'meta' => [
                        'type'      => $productData['meta']['type'],
                        'href'      => $productData['meta']['href'],
                        'mediaType' => 'application/json',
                    ]
                ],
            ];
        }

        if (empty($positions)) {
            Log::warning("Нет подходящих товаров для заказа #{$order->id}");
            return;
        }

        $attributes = [];

        // Пример подстановки атрибутов (тебе подставить свои)
        if ($order->shipping_method) {
            $deliveryUuidMap = [
                'pickup'           => DeliveryTypeEnum::PICKUP,
                'flat'             => DeliveryTypeEnum::KYIV_DELIVERY,
                'novaposhta'       => DeliveryTypeEnum::NOVA_POSHTA_BRANCH,
                'novaposhta_doors' => DeliveryTypeEnum::NOVA_POSHTA_DOOR,
                'my_addresses'     => DeliveryTypeEnum::NOVA_POSHTA_BRANCH,
            ];

            if (isset($deliveryUuidMap[$order->shipping_method])) {
                $deliveryMeta = DeliveryTypeEnum::meta($deliveryUuidMap[$order->shipping_method]);
                if ($deliveryMeta) {
                    $attributes[] = [
                        'meta'  => [
                            'href'      => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/45bc2da9-555f-11ee-0a80-059d0025a2a6',
                            'type'      => 'attributemetadata',
                            'mediaType' => 'application/json',
                        ],
                        'value' => [
                            'meta' => $deliveryMeta['meta'],
                        ]
                    ];
                }
            }
        }

        if ($order->comment) {
            $attributes[] = [
                'meta' => [
                    'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/8be4b192-9276-11e9-9109-f8fc00108f39',
                    'type' => 'attributemetadata',  // <- здесь обязательно так
                    'mediaType' => 'application/json',
                ],
                'value' => $order->comment,
            ];
        }

        if ($order->payment_method) {
            $paymentUuidMap = [
                'cash'          => PaymentTypeEnum::CASH,
                'online'        => PaymentTypeEnum::LIQPAY,
                'bank_transfer' => PaymentTypeEnum::BANK_TRANSFER,
            ];

            if (isset($paymentUuidMap[$order->payment_method])) {
                $paymentMeta = PaymentTypeEnum::meta($paymentUuidMap[$order->payment_method]);
                if ($paymentMeta) {
                    $attributes[] = [
                        'meta'  => [
                            'href'      => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/cc61287b-556c-11ee-0a80-0e5500260c5c',
                            'type'      => 'attributemetadata',
                            'mediaType' => 'application/json',
                        ],
                        'value' => [
                            'meta' => $paymentMeta['meta'],
                        ]
                    ];
                }
            }
        }

        // Формируем тело запроса
        $payload = [
            'organization' => $organization,
            'agent'        => [
                'meta' => $counterparty->meta
            ],
            'positions'    => $positions,
            'moment'       => now()->format('Y-m-d H:i:s'),
        ];

        $comment = '';

        // Добавляем пометку для быстрого заказа
        if ($order->is_fast_order) {
            $comment .= "Швидка покупка\n";
        }

        if (in_array($order->shipping_method, ['novaposhta', 'novaposhta_doors', 'my_addresses'])) {
            $comment .= "\nНаселений пункт: " . $order->city;
            $comment .= "\nВідділення / Адреса: " . $order->shipping_address;
        }

        // Устанавливаем описание, если есть комментарий
        if (!empty(trim($comment))) {
            $payload['description'] = trim($comment);
        }


        if (!empty($attributes)) {
            $payload['attributes'] = $attributes;
        }


        // Временный лог для диагностики ошибки формата
        Log::info('MS customerorder payload', $payload);

        $orderResponse = Http::withBasicAuth(
            config('app.my_store.username'),
            config('app.my_store.password')
        )
            ->withHeaders([
                'Accept-Encoding' => 'gzip',
            ])
            ->post('https://api.moysklad.ru/api/remap/1.2/entity/customerorder', $payload);

        if ($orderResponse->failed()) {
            Log::error('Ошибка создания заказа в МойСклад', ['response' => $orderResponse->json()]);
            throw new \Exception('Ошибка при создании заказа в МойСклад');
        }

        Log::info("Заказ #{$order->id} успешно отправлен в МойСклад", ['ms_order' => $orderResponse->json()]);

        // Сохраняем ID заказа в МойСклад для последующего обновления
        $msOrderId = $orderResponse->json('id');
        if ($msOrderId) {
            $order->update(['moysklad_id' => $msOrderId]);
        }
    }

    /**
     * Обновить данные контрагента в МойСклад при изменении типа доставки
     */
    public static function updateCounterpartyDelivery(Order $order): void
    {
        try {
            $clientEmail = trim((string) ($order->email ?? ''));
            $cleanPhone = self::sanitizePhoneNumber($order->phone);
            $existing = self::findCounterpartyFirstRow($clientEmail, $cleanPhone);

            if ($existing === null) {
                Log::warning("Контрагент не найден для заказа #{$order->id} (email/телефон)", [
                    'email' => $clientEmail,
                ]);

                return;
            }

            $counterpartyMeta = $existing['meta'];

            if (!isset($counterpartyMeta['href'])) {
                Log::error('Некорректный meta в ответе контрагента');
                return;
            }

            $cleanPhone = self::sanitizePhoneNumber($order->phone);
            $attributes = [
                [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3c75f405-660c-11f0-0a80-03cb002a009d", // phone
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $cleanPhone
                ],
                [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/ee963740-660b-11f0-0a80-0d890028be5f", //FIO
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $order->first_name . ' ' . $order->last_name
                ]
            ];

            // Обновляем данные доставки в зависимости от типа
            if (in_array($order->shipping_method, ['novaposhta', 'my_addresses'])) {
                // Определяем тип доставки по shipping_address
                $deliveryType = 'Відділення';
                $deliveryAddress = $order->shipping_address;

                if (strpos($order->shipping_address, 'Кур\'єром:') === 0) {
                    $deliveryType = 'Кур\'єром';
                    $deliveryAddress = str_replace('Кур\'єром: ', '', $order->shipping_address);
                } elseif (strpos($order->shipping_address, 'Доставка по Києву:') === 0) {
                    $deliveryType = 'Доставка по Києву';
                    $deliveryAddress = str_replace('Доставка по Києву: ', '', $order->shipping_address);
                } elseif ($order->novaposhta_warehouse_ref && strpos($order->shipping_address, 'Поштомат') !== false) {
                    // Если есть warehouse_ref и в адресе есть "Поштомат", это почтомат
                    $deliveryType = 'Поштомат';
                    $deliveryAddress = $order->shipping_address;
                }

                // Если есть warehouse_ref, обновляем его
                if ($order->novaposhta_warehouse_ref) {
                    $npValue = self::resolveNpCustomEntityValue(
                        (string) $order->novaposhta_warehouse_ref,
                        self::normalizeNpCounterpartyName((string) $order->shipping_address)
                    );
                    $attributes[] = [
                        "meta" => [
                            "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3999ff42-6610-11f0-0a80-0462002ad32f",
                            "type" => "attributemetadata",
                            "mediaType" => "application/json"
                        ],
                        "value" => $npValue
                    ];
                }

                Log::info("Обновление контрагента для заказа #{$order->id}", [
                    'shipping_method' => $order->shipping_method,
                    'shipping_address' => $order->shipping_address,
                    'delivery_type' => $deliveryType,
                    'delivery_address' => $deliveryAddress,
                    'warehouse_ref' => $order->novaposhta_warehouse_ref,
                    'has_warehouse_ref' => !empty($order->novaposhta_warehouse_ref)
                ]);
            }

            $updateResponse = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                    'Content-Type' => 'application/json'
                ])
                ->put($counterpartyMeta['href'], array_filter([
                    'attributes' => $attributes,
                    'email' => $clientEmail !== '' ? $clientEmail : null,
                ]));

            if ($updateResponse->successful()) {
                Log::info("Контрагент успешно обновлен для заказа #{$order->id}");
            } else {
                Log::error('Не удалось обновить атрибуты контрагента', [
                    'order_id' => $order->id,
                    'response' => $updateResponse->json()
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Ошибка обновления контрагента для заказа #{$order->id}", [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Фільтри API МС для телефону: спочатку +380 та 380 (як у картці), далі 0XX, останні 9 цифр.
     *
     * @return list<string>
     */
    private static function counterpartyPhoneFilterValues(string $cleanPhone): array
    {
        $digits = preg_replace('/\D/u', '', $cleanPhone) ?? '';
        if ($digits === '') {
            return [];
        }

        $intlPhone = $digits;
        $localPhone = $digits;
        if (strpos($digits, '380') === 0 && strlen($digits) >= 12) {
            $localPhone = '0'.substr($digits, 3);
        }

        $plusIntl = '+'.$intlPhone;
        $last9 = strlen($digits) >= 9 ? substr($digits, -9) : '';

        $filters = [
            'phone~'.$plusIntl,
            'phone~'.$intlPhone,
            'phone~'.$localPhone,
        ];
        if ($last9 !== '' && strlen($last9) === 9) {
            $filters[] = 'phone~'.$last9;
        }

        return array_values(array_unique($filters));
    }

    /**
     * Пошук контрагента: email → варіанти телефону.
     *
     * @return array<string, mixed>|null
     */
    private static function findCounterpartyFirstRow(string $clientEmail, string $cleanPhone): ?array
    {
        $tryFilters = [];
        if ($clientEmail !== '') {
            $tryFilters[] = 'email='.$clientEmail;
        }
        foreach (self::counterpartyPhoneFilterValues($cleanPhone) as $phoneFilter) {
            $tryFilters[] = $phoneFilter;
        }

        foreach ($tryFilters as $filter) {
            $response = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                ])->get('https://api.moysklad.ru/api/remap/1.2/entity/counterparty', [
                    'filter' => $filter,
                ]);

            if ($response->successful() && count($response->json('rows') ?? []) > 0) {
                return $response->json('rows')[0];
            }
        }

        return null;
    }

    /**
     * Обновить статус заказа в МойСклад после оплаты
     */
    public static function updateOrderPaymentStatus(Order $order): void
    {
        if (!$order->moysklad_id) {
            Log::warning("Заказ #{$order->id} не имеет ID в МойСклад");
            return;
        }

        try {
            $sklad = MoySklad::getInstance(
                config('app.my_store.username'),
                config('app.my_store.password')
            );

            // Получаем текущий заказ из МойСклад
            $response = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                ])
                ->get("https://api.moysklad.ru/api/remap/1.2/entity/customerorder/{$order->moysklad_id}");

            if ($response->failed()) {
                Log::error("Ошибка получения заказа #{$order->id} из МойСклад", ['response' => $response->json()]);
                return;
            }

            $msOrder = $response->json();

            // Обновляем атрибут "Оплачено" на true
            $updatePayload = [
                'attributes' => [
                    [
                        'meta' => [
                            'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/2eec0380-4d6c-11ee-0a80-108a000d82b9',
                            'type' => 'attributemetadata',
                            'mediaType' => 'application/json'
                        ],
                        'value' => true
                    ]
                ]
            ];

            $updateResponse = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                    'Content-Type' => 'application/json'
                ])
                ->put("https://api.moysklad.ru/api/remap/1.2/entity/customerorder/{$order->moysklad_id}", $updatePayload);

            if ($updateResponse->successful()) {
                Log::info("Статус заказа #{$order->id} успешно обновлен в МойСклад (Оплачено: true)");
            } else {
                Log::error("Ошибка обновления статуса заказа #{$order->id} в МойСклад", ['response' => $updateResponse->json()]);
            }

        } catch (\Exception $e) {
            Log::error("Ошибка обновления статуса заказа #{$order->id} в МойСклад", ['error' => $e->getMessage()]);
        }
    }

    /**
     * Получить meta объект из SDK-сущности через Reflection,
     * т.к. свойство storage — приватное
     *
     * @param object $entity
     *
     * @return array|null
     */
    private static function getEntityMeta($entity): ?array
    {
        $fields = $entity->fields;

        $reflectionFields = new ReflectionClass($fields);
        $propertyFields = $reflectionFields->getProperty('storage');
        $propertyFields->setAccessible(true);
        $storage = $propertyFields->getValue($fields);

        $metaField = $storage->meta ?? null;

        if (!$metaField) {
            return null;
        }

        $reflectionMeta = new ReflectionClass($metaField);
        $propertyMeta = $reflectionMeta->getProperty('storage');
        $propertyMeta->setAccessible(true);
        $metaStorage = $propertyMeta->getValue($metaField);

        return (array)$metaStorage;
    }

    /**
     * Отправить консультацию в МойСклад
     */
    public static function sendConsultation(\App\Models\Consultation $consultation): void
    {
        $sklad = MoySklad::getInstance(
            config('app.my_store.username'),
            config('app.my_store.password')
        );

        $organizationsList = Organization::query($sklad)->getList();
        $organization = $organizationsList[0] ?? null;

        if (!$organization) {
            throw new \Exception('Организация не найдена');
        } else {
            $organizationMeta = $organization->fields->meta;

            $organization = [
                'meta' => [
                    'href'      => $organizationMeta->href,
                    'type'      => $organizationMeta->type,
                    'mediaType' => $organizationMeta->mediaType,
                ]
            ];
        }

        $clientName = trim($consultation->name);
        $clientEmail = trim($consultation->email ?? '');
        $cleanPhone = self::sanitizePhoneNumber($consultation->phone);

        $response = null;
        $tryFilters = [];
        if ($clientEmail !== '') {
            $tryFilters[] = 'email=' . $clientEmail;
        }
        foreach (self::counterpartyPhoneFilterValues($cleanPhone) as $phoneFilter) {
            $tryFilters[] = $phoneFilter;
        }

        foreach ($tryFilters as $filter) {
            $response = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                ])->get('https://api.moysklad.ru/api/remap/1.2/entity/counterparty', [
                    'filter' => $filter,
                ]);

            if ($response->successful() && count($response->json('rows') ?? []) > 0) {
                break;
            }
        }

        if ($response->successful() && count($response->json('rows')) > 0) {
            $existing = $response->json('rows')[0];
            $counterpartyMeta = $existing['meta'];

            if (!isset($counterpartyMeta['href'])) {
                throw new \Exception('Некорректный meta в ответе контрагента');
            }

            $counterparty = new Counterparty($sklad);
            $counterparty->meta = [
                'type'      => $counterpartyMeta['type'] ?? 'counterparty',
                'href'      => $counterpartyMeta['href'],
                "mediaType" => "application/json"
            ];

            $attributes = [
                [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3c75f405-660c-11f0-0a80-03cb002a009d", // phone
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $cleanPhone
                ],
                [
                    "meta" => [
                        "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/ee963740-660b-11f0-0a80-0d890028be5f", //FIO
                        "type" => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $clientName
                ]
            ];

            $updateResponse = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password'))
                    ->withHeaders([
                        'Accept-Encoding' => 'gzip',
                    ])->put($counterpartyMeta['href'], [
                    'attributes' => $attributes
                ]);

            if ($updateResponse->failed()) {
                Log::error('Не удалось обновить атрибуты контрагента для консультации', [
                    'response' => $updateResponse->json()
                ]);
            }

        } else {
            $counterparty = new Counterparty($sklad);
            $counterparty->name = $clientName !== '' ? $clientName : $cleanPhone;
            if ($clientEmail !== '') {
                $counterparty->email = $clientEmail;
            }
            $counterparty->phone = $cleanPhone;

            $attributes = [
                [
                    "meta"  => [
                        "href"      => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/3c75f405-660c-11f0-0a80-03cb002a009d", // Phone
                        "type"      => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    "value" => $cleanPhone
                ],
               [
                   "meta" => [
                       "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/ee963740-660b-11f0-0a80-0d890028be5f", //FIO
                       "type" => "attributemetadata",
                       "mediaType" => "application/json"
                   ],
                   "value" => $clientName
               ]
            ];

            $counterparty->attributes = $attributes;
            $counterparty = $counterparty->create();

            // Получаем meta через рефлексию
            $counterpartyMeta = self::getEntityMeta($counterparty);
            $counterparty->meta = [
                'type'      => $counterpartyMeta['type'] ?? 'counterparty',
                'href'      => $counterpartyMeta['href'],
                'mediaType' => 'application/json',
            ];
        }

        // Получаем информацию о товаре для консультации
        $productResponse = Http::withBasicAuth(
            config('app.my_store.username'),
            config('app.my_store.password')
        )
            ->withHeaders([
                'Accept-Encoding' => 'gzip',
            ])
            ->get('https://api.moysklad.ru/api/remap/1.2/entity/product', [
                'filter' => 'code=' . $consultation->product->code,
            ]);

        $productData = $productResponse->json('rows')[0] ?? null;

        // Формируем позиции заказа - используем реальный товар
        $positions = [];
        if ($productData) {
            // Получаем цену товара в USD центах
            $unitPriceUah = (float) $consultation->product->getUnitPriceForQuantity(1);
            $unitPriceUsd = (float) $consultation->product->getPriceByDollars($unitPriceUah);

            $positions[] = [
                'quantity'   => 1.0,
                'price'      => (int) round($unitPriceUsd * 100), // Реальная цена товара
                'assortment' => [
                    'meta' => [
                        'type'      => $productData['meta']['type'],
                        'href'      => $productData['meta']['href'],
                        'mediaType' => 'application/json',
                    ]
                ],
            ];
        } else {
            // Если товар не найден в МойСклад, создаем позицию с нулевой ценой
            $positions[] = [
                'quantity'   => 1.0,
                'price'      => 0,
                'assortment' => [
                    'meta' => [
                        'type'      => 'service',
                        'href'      => 'https://api.moysklad.ru/api/remap/1.2/entity/service/00000000-0000-0000-0000-000000000000',
                        'mediaType' => 'application/json',
                    ]
                ],
            ];
        }

        // Формируем комментарий с пометкой о консультации на украинском
        $comment = "КОНСУЛЬТАЦІЯ\n";
        $comment .= "Товар: " . $consultation->product->name . "\n";
        if ($consultation->comment) {
            $comment .= "Коментар клієнта: " . $consultation->comment . "\n";
        }

        // Формируем тело запроса
        $payload = [
            'organization' => $organization,
            'agent'        => [
                'meta' => $counterparty->meta
            ],
            'positions'    => $positions,
            'moment'       => now()->format('Y-m-d H:i:s'),
            'description'  => $comment,
        ];

        // Временный лог для диагностики
        Log::info('MS consultation payload', $payload);

        $orderResponse = Http::withBasicAuth(
            config('app.my_store.username'),
            config('app.my_store.password')
        )
            ->withHeaders([
                'Accept-Encoding' => 'gzip',
            ])
            ->post('https://api.moysklad.ru/api/remap/1.2/entity/customerorder', $payload);

        if ($orderResponse->failed()) {
            Log::error('Помилка створення консультації в МойСклад', ['response' => $orderResponse->json()]);
            throw new \Exception('Помилка при створенні консультації в МойСклад');
        }

        Log::info("Консультація #{$consultation->id} успішно відправлена в МойСклад", ['ms_order' => $orderResponse->json()]);

        // Сохраняем ID заказа в МойСклад для последующего обновления
        $msOrderId = $orderResponse->json('id');
        if ($msOrderId) {
            $consultation->update(['moysklad_id' => $msOrderId]);
        }
    }

    public static function sanitizePhoneNumber($phone)
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strpos($digits, '380') === 0) {
            return $digits;
        } elseif (strpos($digits, '80') === 0) {
            return '3' . $digits;
        } elseif (strpos($digits, '0') === 0) {
            return '38' . $digits;
        }

        return $digits; // на всякий случай
    }

    /**
     * МС может хранить несколько текстовых вариантов для одного ref.
     * Нормализуем подпись НП и убираем хвост "(місто, адреса)", если он продублирован.
     */
    private static function normalizeNpCounterpartyName(string $shippingAddress): string
    {
        $value = trim($shippingAddress);
        if ($value === '') {
            return $value;
        }

        // Для кейса вида: "Обухів, Поштомат ...": прибираємо префікс міста.
        if (preg_match('/^[^,]+,\s*(Поштомат.+)$/u', $value, $m)) {
            $value = trim($m[1]);
        }

        // Для кейса вида: "... (ТІЛЬКИ ДЛЯ МЕШКАНЦІВ) (Обухів, Київська, ...)"
        if (str_contains($value, 'Поштомат') && preg_match('/^(.+?)\s+\(([^()]*)\)\s*$/u', $value, $m)) {
            $base = trim($m[1]);
            $tail = mb_strtolower(trim($m[2]), 'UTF-8');
            if (
                str_starts_with($tail, 'обух') ||
                str_starts_with($tail, 'київ') ||
                str_starts_with($tail, 'киев')
            ) {
                return $base;
            }
        }

        return $value;
    }

    /**
     * Пытаемся подтянуть value из справочника МС по ref НП:
     * 1) прямой GET по id (если id МС совпадает с ref НП),
     * 2) поиск по externalCode,
     * 3) поиск по code.
     * Если не нашли — fallback на локально собранный meta+name.
     *
     * @return array{meta: array<string, string>, name: string}
     */
    private static function resolveNpCustomEntityValue(string $npRef, string $fallbackName): array
    {
        $dictId = '710e5a69-63be-11f0-0a80-03cc0016c7e1';
        $base = "https://api.moysklad.ru/api/remap/1.2/entity/customentity/{$dictId}";

        $direct = Http::withBasicAuth(
            config('app.my_store.username'),
            config('app.my_store.password')
        )->withHeaders([
            'Accept-Encoding' => 'gzip',
        ])->get("{$base}/{$npRef}");

        if ($direct->successful() && is_array($direct->json())) {
            $row = $direct->json();
            if (!empty($row['meta']['href'])) {
                return [
                    'meta' => [
                        'href' => (string) $row['meta']['href'],
                        'type' => 'customentity',
                        'mediaType' => 'application/json',
                    ],
                    'name' => (string) ($row['name'] ?? $fallbackName),
                ];
            }
        }

        foreach (['externalCode', 'code'] as $field) {
            $resp = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )->withHeaders([
                'Accept-Encoding' => 'gzip',
            ])->get($base, [
                'filter' => "{$field}={$npRef}",
                'limit' => 1,
            ]);

            if ($resp->successful()) {
                $row = $resp->json('rows.0');
                if (is_array($row) && !empty($row['meta']['href'])) {
                    return [
                        'meta' => [
                            'href' => (string) $row['meta']['href'],
                            'type' => 'customentity',
                            'mediaType' => 'application/json',
                        ],
                        'name' => (string) ($row['name'] ?? $fallbackName),
                    ];
                }
            }
        }

        return [
            'meta' => [
                'href' => "{$base}/{$npRef}",
                'type' => 'customentity',
                'mediaType' => 'application/json',
            ],
            'name' => $fallbackName,
        ];
    }

}
