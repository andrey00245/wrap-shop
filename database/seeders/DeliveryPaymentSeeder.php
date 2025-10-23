<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryOption;
use App\Models\PaymentOption;

class DeliveryPaymentSeeder extends Seeder
{
    public function run()
    {
        // Опции доставки
        $deliveryOptions = [
            [
                'name' => [
                    'uk' => 'Нова Пошта',
                    'ru' => 'Новая Почта',
                    'en' => 'Nova Poshta'
                ],
                'description' => [
                    'uk' => 'Доставка по всій Україні через відділення Нової Пошти. Термін доставки 1-3 дні.',
                    'ru' => 'Доставка по всей Украине через отделения Новой Почты. Срок доставки 1-3 дня.',
                    'en' => 'Delivery across Ukraine through Nova Poshta branches. Delivery time 1-3 days.'
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => [
                    'uk' => 'Кур\'єрська доставка',
                    'ru' => 'Курьерская доставка',
                    'en' => 'Courier delivery'
                ],
                'description' => [
                    'uk' => 'Доставка кур\'єром за вказаною адресою. Термін доставки 1-2 дні.',
                    'ru' => 'Доставка курьером по указанному адресу. Срок доставки 1-2 дня.',
                    'en' => 'Courier delivery to the specified address. Delivery time 1-2 days.'
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => [
                    'uk' => 'Самовивіз',
                    'ru' => 'Самовывоз',
                    'en' => 'Pickup'
                ],
                'description' => [
                    'uk' => 'Самовивіз з нашого складу. Адреса: м. Київ, вул. Прикладна, 123.',
                    'ru' => 'Самовывоз с нашего склада. Адрес: г. Киев, ул. Прикладная, 123.',
                    'en' => 'Pickup from our warehouse. Address: Kyiv, Prykladna str., 123.'
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($deliveryOptions as $option) {
            DeliveryOption::create($option);
        }

        // Опции оплаты
        $paymentOptions = [
            [
                'name' => [
                    'uk' => 'Приват24 за QR-кодом',
                    'ru' => 'Приват24 по QR-коду',
                    'en' => 'Privat24 by QR code'
                ],
                'description' => [
                    'uk' => 'Оплата через додаток Приват24 за QR-кодом. Швидко та зручно.',
                    'ru' => 'Оплата через приложение Приват24 по QR-коду. Быстро и удобно.',
                    'en' => 'Payment through Privat24 app by QR code. Fast and convenient.'
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => [
                    'uk' => 'Visa/Mastercard',
                    'ru' => 'Visa/Mastercard',
                    'en' => 'Visa/Mastercard'
                ],
                'description' => [
                    'uk' => 'Оплата карткою Visa або Mastercard. Безпечно та надійно.',
                    'ru' => 'Оплата картой Visa или Mastercard. Безопасно и надежно.',
                    'en' => 'Payment by Visa or Mastercard. Safe and reliable.'
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => [
                    'uk' => 'Накладений платіж',
                    'ru' => 'Наложенный платеж',
                    'en' => 'Cash on delivery'
                ],
                'description' => [
                    'uk' => 'Оплата при отриманні товару. Додаткова комісія 2% від суми замовлення.',
                    'ru' => 'Оплата при получении товара. Дополнительная комиссия 2% от суммы заказа.',
                    'en' => 'Payment upon receipt of goods. Additional commission 2% of order amount.'
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($paymentOptions as $option) {
            PaymentOption::create($option);
        }
    }
}