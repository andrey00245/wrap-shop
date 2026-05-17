<?php

namespace App\Support;

/**
 * FAQ для schema.org: окремо uk / ru у полі faq_items.
 *
 * {"uk":[{"question":"...","answer":"..."}],"ru":[...]}
 *
 * Старий формат (масив без локалей) — українська версія.
 */
final class LocaleFaqItems
{
    public const LOCALES = ['uk', 'ru'];

    public const DEFAULT_LOCALE = 'uk';

    /**
     * @param  array<string, mixed>|null  $stored
     * @return list<array{question: string, answer: string}>
     */
    public static function forLocale(?array $stored, string $locale): array
    {
        if (! is_array($stored) || $stored === []) {
            return [];
        }

        if (self::isLocaleMap($stored)) {
            $items = self::sanitizeList(is_array($stored[$locale] ?? null) ? $stored[$locale] : []);

            if ($items === [] && $locale !== self::DEFAULT_LOCALE) {
                $items = self::sanitizeList(is_array($stored[self::DEFAULT_LOCALE] ?? null) ? $stored[self::DEFAULT_LOCALE] : []);
            }

            return $items;
        }

        return self::sanitizeList($stored);
    }

    /**
     * @param  array<string, mixed>|null  $stored
     * @param  list<array{question?: string, answer?: string}>|array<int, mixed>  $items
     * @return array<string, list<array{question: string, answer: string}>>
     */
    public static function withLocale(?array $stored, string $locale, array $items): array
    {
        $map = self::isLocaleMap($stored ?? []) ? $stored : ['uk' => self::sanitizeList($stored ?? [])];

        foreach (self::LOCALES as $code) {
            if (! array_key_exists($code, $map)) {
                $map[$code] = [];
            }
        }

        $map[$locale] = self::sanitizeList($items);

        return array_intersect_key($map, array_flip([...self::LOCALES, 'en']));
    }

    /**
     * @param  array<string, mixed>  $stored
     */
    public static function isLocaleMap(array $stored): bool
    {
        if ($stored === [] || array_is_list($stored)) {
            return false;
        }

        foreach (self::LOCALES as $locale) {
            if (array_key_exists($locale, $stored)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<array{question?: string, answer?: string}>|array<int, mixed>  $items
     * @return list<array{question: string, answer: string}>
     */
    public static function sanitizeList(array $items): array
    {
        $out = [];

        foreach ($items as $row) {
            if (! is_array($row)) {
                continue;
            }
            $q = trim((string) ($row['question'] ?? ''));
            $a = trim((string) ($row['answer'] ?? ''));
            if ($q !== '' && $a !== '') {
                $out[] = ['question' => $q, 'answer' => $a];
            }
        }

        return $out;
    }

    public static function translationOrDefault(object $model, string $attribute, string $locale): string
    {
        $value = trim((string) $model->getTranslation($attribute, $locale, false));

        if ($value !== '' || $locale === self::DEFAULT_LOCALE) {
            return $value;
        }

        return trim((string) $model->getTranslation($attribute, self::DEFAULT_LOCALE, false));
    }

    /**
     * @return array{question: string, answer: string}|null
     */
    public static function rowFromFaqModel(\App\Models\Faq $faq, string $locale): ?array
    {
        $question = self::translationOrDefault($faq, 'question', $locale);
        $answer = self::translationOrDefault($faq, 'answer', $locale);

        if ($question === '' || $answer === '') {
            return null;
        }

        return ['question' => $question, 'answer' => $answer];
    }

    /**
     * @param  list<array{question: string, answer: string}>  $items
     * @return list<array<string, mixed>>
     */
    public static function schemaMainEntity(array $items): array
    {
        return array_map(static function (array $row): array {
            return [
                '@type' => 'Question',
                'name' => $row['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($row['answer']),
                ],
            ];
        }, $items);
    }

    /**
     * @param  list<array{question: string, answer: string}>  $items
     * @return array<string, mixed>|null
     */
    public static function faqPageSchema(array $items): ?array
    {
        if ($items === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => self::schemaMainEntity($items),
        ];
    }
}
