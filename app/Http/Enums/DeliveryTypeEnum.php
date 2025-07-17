<?php

namespace App\Http\Enums;

class DeliveryTypeEnum
{
    const PICKUP = '1d519cd2-555f-11ee-0a80-059d0025a013'; // Самовивіз з ательє м. Київ, вул. Ізюмська 5а
    const NOVA_POSHTA_DOOR = '24847066-555f-11ee-0a80-01c70025509f'; // Кур'єр Нової Пошти
    const NOVA_POSHTA_BRANCH = '2aaec283-555f-11ee-0a80-0f420024cda8'; // Відділення Нової Пошти
    const KYIV_DELIVERY = 'b2a97b52-7fcd-11ee-0a80-0980002c93f8'; // Доставка по Києву

    public static function meta($value): array
    {
        return [
            'meta' => [
                'href'      => "https://api.moysklad.ru/api/remap/1.2/entity/customentity/0344fae4-555f-11ee-0a80-0b3a00241a74/{$value}",
                'type'      => 'customentity',
                'mediaType' => 'application/json',
            ]
        ];
    }
}
