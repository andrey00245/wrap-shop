<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Телефон для відображення на сайті та в schema.org (E.164).
 * У JSON-LD не використовуємо phone_view — там часто HTML або обрізаний «+38».
 */
final class SitePhone
{
    private const FALLBACK_E164 = '+380660003202';

    public static function schemaNumber(?Setting $settings): string
    {
        $e164 = self::toE164(self::digits($settings?->phone));

        return $e164 !== '' ? $e164 : self::FALLBACK_E164;
    }

    public static function telHref(?Setting $settings): string
    {
        $e164 = self::toE164(self::digits($settings?->phone));

        return $e164 !== '' ? 'tel:'.$e164 : '#';
    }

    /**
     * Текст для посилання tel: / шапки (без HTML).
     */
    public static function display(?Setting $settings): string
    {
        return self::displayFromFields($settings?->phone, $settings?->phone_view);
    }

    public static function displayAdditional(?Setting $settings): string
    {
        return self::displayFromFields($settings?->phone_aditional, $settings?->phone_aditional_view);
    }

    public static function telHrefAdditional(?Setting $settings): string
    {
        $e164 = self::toE164(self::digits($settings?->phone_aditional));

        return $e164 !== '' ? 'tel:'.$e164 : '#';
    }

    private static function displayFromFields(?string $phone, ?string $phoneView): string
    {
        $view = trim(strip_tags((string) ($phoneView ?? '')));
        $digits = self::digits($phone);

        if ($view !== '' && self::digits($view) !== '' && mb_strlen(self::digits($view)) >= 10) {
            return $view;
        }

        $e164 = self::toE164($digits);

        return $e164 !== '' ? self::formatHuman($e164) : '';
    }

    public static function digits(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }

    public static function toE164(string $digits): string
    {
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '380')) {
            return '+'.$digits;
        }

        if (str_starts_with($digits, '0')) {
            return '+38'.$digits;
        }

        return '+'.$digits;
    }

    public static function formatHuman(string $e164): string
    {
        $digits = self::digits($e164);

        if (strlen($digits) === 12 && str_starts_with($digits, '380')) {
            return sprintf(
                '+%s (%s) %s-%s-%s',
                '38',
                substr($digits, 2, 2),
                substr($digits, 4, 3),
                substr($digits, 7, 2),
                substr($digits, 9, 2)
            );
        }

        return $e164;
    }
}
