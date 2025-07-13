<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class TranslationService
{
    public function translate(string $html, string $targetLanguage): string
    {
        $prompt = <<<PROMPT
Ты — профессиональный переводчик.

Переведи следующий текст на {$targetLanguage}.
Сохрани оригинальные символы и коды модели (например, "3M 1080-M230") без изменений.
Переводи все остальное полностью, даже если слова уже похожи на {$targetLanguage}.

Верни только переведённый текст, без пояснений и разметки.

{$html}
PROMPT;

        $result = OpenAI::chat()->create([
            'model'       => 'gpt-4o',
            'messages'    => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
        ]);

        $content = $result['choices'][0]['message']['content'] ?? '';

        $clean = preg_replace('/```html|```/', '', $content);

        return trim($clean);
    }

    public function translateWithHtml(string $html, string $targetLanguage): string
    {
        $prompt = <<<PROMPT
Переведи следующий HTML-контент на {$targetLanguage}.
❗ ВАЖНО: Не добавляй пояснений, комментариев или markdown.
Верни только HTML, в котором переведён только текст, теги не меняй если они есть.

{$html}
PROMPT;

        $result = OpenAI::chat()->create([
            'model'       => 'gpt-4o',
            'messages'    => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
        ]);

        $content = $result['choices'][0]['message']['content'] ?? '';

        $clean = preg_replace('/```html|```/', '', $content);

        return trim($clean);
    }
}
