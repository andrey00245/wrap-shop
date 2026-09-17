<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class TranslationService
{
    public function translate(string $html, string $targetLanguage): string
    {
        $isRussian = $this->isRussianTarget($targetLanguage);
        $isEnglish = $this->isEnglishTarget($targetLanguage);

        $languageRules = '';
        if ($isRussian) {
            $languageRules = <<<RULES
КРИТИЧНО для русского:
- Пиши именно на РУССКОМ языке (кириллица), НЕ на английском и НЕ на украинском.
- Пример: «Тип» → «Тип», НЕ «Type». «Бренд» → «Бренд», НЕ «Brand».
- Английские слова допустимы только для брендов, артикулов и кодов моделей (3M, Avery, GYEON).
RULES;
        } elseif ($isEnglish) {
            $languageRules = <<<RULES
КРИТИЧНО для английского:
- Пиши именно на английском (латиница).
- Пример: «Тип» → «Type», «Форма випуску» → «Release form».
RULES;
        }

        $prompt = <<<PROMPT
Ты — профессиональный переводчик интернет-магазина автотоваров.

Переведи следующий текст на {$targetLanguage}.
Сохрани оригинальные символы и коды модели (например, "3M 1080-M230") без изменений.
Переводи все остальное полностью под целевой язык.

{$languageRules}

Верни только переведённый текст, без пояснений и разметки.

{$html}
PROMPT;

        $result = OpenAI::chat()->create([
            'model'       => 'gpt-4o',
            'messages'    => [
                ['role' => 'system', 'content' => 'You translate ecommerce catalog fields. Never put English text into a Russian translation field unless the source is a brand/model code.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        $content = $result['choices'][0]['message']['content'] ?? '';

        $clean = preg_replace('/```html|```/', '', $content);

        return trim($clean);
    }

    public function translateWithHtml(string $html, string $targetLanguage): string
    {
        $isRussian = $this->isRussianTarget($targetLanguage);

        $languageRules = $isRussian
            ? "КРИТИЧНО: русский перевод должен быть на русском (кириллица), не на английском. Английский только в брендах/кодах."
            : '';

        $prompt = <<<PROMPT
Переведи следующий HTML-контент на {$targetLanguage}.
❗ ВАЖНО: Не добавляй пояснений, комментариев или markdown.
Верни только HTML, в котором переведён только текст, теги не меняй если они есть.
{$languageRules}

{$html}
PROMPT;

        $result = OpenAI::chat()->create([
            'model'       => 'gpt-4o',
            'messages'    => [
                ['role' => 'system', 'content' => 'You translate ecommerce HTML. Russian output must be Russian, not English.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        $content = $result['choices'][0]['message']['content'] ?? '';

        $clean = preg_replace('/```html|```/', '', $content);

        return trim($clean);
    }

    private function isRussianTarget(string $targetLanguage): bool
    {
        $normalized = mb_strtolower($targetLanguage);

        return str_contains($normalized, 'рус') || str_contains($normalized, 'russian') || $normalized === 'ru';
    }

    private function isEnglishTarget(string $targetLanguage): bool
    {
        $normalized = mb_strtolower($targetLanguage);

        return str_contains($normalized, 'англ') || str_contains($normalized, 'english') || $normalized === 'en';
    }
}
