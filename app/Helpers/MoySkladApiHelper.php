<?php

namespace App\Helpers;

use Illuminate\Http\Client\Response;

/**
 * Розбір відповідей JSON API МойСклад (поле errors[]).
 *
 * @see https://dev.moysklad.ru/doc/api/remap/1.2/#/errors
 */
class MoySkladApiHelper
{
    /**
     * Людинозчитуваний текст помилок з тіла відповіді або сирий уривок.
     */
    public static function formatErrorsFromResponse(Response $response): string
    {
        $body = $response->json();
        $fromJson = self::formatErrorsFromBody($body);
        if ($fromJson !== '') {
            return $fromJson;
        }

        $raw = $response->body();
        if ($raw !== '' && $raw !== 'null') {
            return mb_substr(trim($raw), 0, 2000);
        }

        return 'HTTP '.$response->status();
    }

    /**
     * @param  array<string, mixed>|null  $body
     */
    public static function formatErrorsFromBody(?array $body): string
    {
        if ($body === null || empty($body['errors']) || ! is_array($body['errors'])) {
            return '';
        }

        $parts = [];
        foreach ($body['errors'] as $err) {
            if (is_string($err)) {
                $parts[] = $err;

                continue;
            }
            if (! is_array($err)) {
                $parts[] = (string) $err;

                continue;
            }
            $text = $err['error'] ?? $err['message'] ?? null;
            if ($text !== null && $text !== '') {
                $code = $err['code'] ?? null;
                $parts[] = $code !== null ? "[{$code}] {$text}" : $text;

                continue;
            }
            $parts[] = json_encode($err, JSON_UNESCAPED_UNICODE);
        }

        return implode('; ', array_filter($parts));
    }
}
