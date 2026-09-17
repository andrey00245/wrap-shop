<?php

namespace App\Support;

class TranslationCompleteness
{
    /**
     * Переклад готовий, якщо ru/en не порожні.
     * Для кириличного uk:
     * - en не копія uk;
     * - ru не «підмінений» англійською (Тип → Type у ru).
     * Латиниця/бренди (3M) — ок однаковими.
     */
    public static function isComplete(?string $uk, ?string $ru, ?string $en): bool
    {
        $uk = trim(strip_tags((string) $uk));
        $ru = trim(strip_tags((string) $ru));
        $en = trim(strip_tags((string) $en));

        if ($ru === '' || $en === '') {
            return false;
        }

        if ($uk === '') {
            return true;
        }

        if (! self::containsCyrillic($uk)) {
            return true;
        }

        // Кирилиця в uk: en має відрізнятися (інакше це копіпаст)
        if ($en === $uk) {
            return false;
        }

        // Усі три однакові — явно не перекладено
        if ($uk === $ru && $uk === $en) {
            return false;
        }

        // uk кирилицею, а ru без кирилиці і збігається з en → ймовірно EN підставили в RU
        // («Тип» / «Type» / «Type»)
        if (! self::containsCyrillic($ru) && self::isMostlyLatin($ru) && mb_strtolower($ru) === mb_strtolower($en)) {
            return false;
        }

        // uk кирилицею, а ru повністю латиницею (не бренд-короткий код) — підозріло
        if (! self::containsCyrillic($ru) && self::isMostlyLatin($ru) && mb_strlen($ru) > 2) {
            return false;
        }

        return true;
    }

    public static function containsCyrillic(string $text): bool
    {
        return (bool) preg_match('/\p{Cyrillic}/u', $text);
    }

    public static function isMostlyLatin(string $text): bool
    {
        $trimmed = trim($text);

        if ($trimmed === '') {
            return false;
        }

        return (bool) preg_match('/^[A-Za-z0-9 .\-\_\+\&\/#\'\"]+$/u', $trimmed);
    }
}
