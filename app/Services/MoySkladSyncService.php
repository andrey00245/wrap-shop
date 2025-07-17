<?php

namespace App\Services;

use App\Http\Enums\DeliveryTypeEnum;
use App\Http\Enums\PaymentTypeEnum;
use App\Models\FastOrder;
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
    public static function sendOrder(Order|FastOrder $order): void
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

        $response = Http::withBasicAuth(
            config('app.my_store.username'),
            config('app.my_store.password')
        )
            ->withHeaders([
                'Accept-Encoding' => 'gzip',
            ])->get('https://api.moysklad.ru/api/remap/1.2/entity/counterparty', [
                'filter' => 'email=' . $order->email,
            ]);

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
        } else {
            $counterparty = new Counterparty($sklad);
            $counterparty->name = $order->first_name . ' ' . $order->last_name;
            $counterparty->email = $order->email;
            $counterparty->phone = $order->phone;
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
        if ($order instanceof Order){
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

                $positions[] = [
                    'quantity'   => (float)$orderProduct->pivot->quantity,
                    'price'      => $orderProduct->getPriceByDollars($orderProduct->pivot->price) * 100, // цена в доларах
                    'assortment' => [
                        'meta' => [
                            'type'      => $productData['meta']['type'],
                            'href'      => $productData['meta']['href'],
                            'mediaType' => 'application/json',
                        ]
                    ],
                ];
            }
        }
        else{
            $productResponse = Http::withBasicAuth(
                config('app.my_store.username'),
                config('app.my_store.password')
            )
                ->withHeaders([
                    'Accept-Encoding' => 'gzip',
                ])
                ->get('https://api.moysklad.ru/api/remap/1.2/entity/product', [
                    'filter' => 'code=' . $order->product->code,
                ]);

            $productData = $productResponse->json('rows')[0] ?? null;

            if (!$productData) {
                Log::warning("Не найден товар с кодом {order->product->code}");
            }

            $positions[] = [
                'quantity'   => (float)$order->quantity,
                'price'      => $order->product->getPriceByDollars($order->product->getPriceByCount($order->quantity)) * 100, // цена в доларах
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

        // Атрибуты — пример, подставь свои значения и UUID атрибутов
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
            'name'         => 'Wrap #' . $order->id,
            'organization' => $organization,
            'agent'        => [
                'meta' => $counterparty->meta
            ],
            'positions'    => $positions,
            'moment'       => now()->format('Y-m-d H:i:s'),
        ];

        if (!empty($attributes)) {
            $payload['attributes'] = $attributes;
        }


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
}
