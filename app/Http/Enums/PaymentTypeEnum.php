<?php

namespace App\Http\Enums;

class PaymentTypeEnum
{
    // Оплата готівкою (при отриманні)
    const CASH = '3e328bed-5567-11ee-0a80-118600253342';

    // Онлайн оплата (Visa, MasterCard, GooglePay, ApplePay)
    const LIQPAY = '59c88d8e-5567-11ee-0a80-10220025afdf';

    // Оплата онлайн (Liqpay) — еще одна сущность, возможно продублированная
    const LIQPAY_ALT = '96bc4296-7fcd-11ee-0a80-049b002b0296';

    // Банківський переказ / Виставити рахунок
    const BANK_TRANSFER = 'efaa8447-555e-11ee-0a80-0e550025361e';

    protected static string $customEntityId = '3ad67384-555e-11ee-0a80-14a80024fef6';

    public static function meta(string $value): array
    {
        return [
            'meta' => [
                'href'      => "https://api.moysklad.ru/api/remap/1.2/entity/customentity/" . self::$customEntityId . "/{$value}",
                'type'      => 'customentity',
                'mediaType' => 'application/json',
            ]
        ];
    }
}
