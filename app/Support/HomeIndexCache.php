<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class HomeIndexCache
{
    public const VERSION = 'v8';

    public const TAG = 'home';

    private const TTL = 120;

    public static function key(string $locale): string
    {
        return 'home.index.'.self::VERSION.'.'.$locale;
    }

    /** @return list<string> */
    public static function supportedLocales(): array
    {
        return array_keys(config('laravellocalization.supportedLocales', [
            'uk' => true,
            'en' => true,
            'ru' => true,
        ]));
    }

    public static function remember(string $locale, callable $callback): mixed
    {
        if (Cache::supportsTags()) {
            return Cache::tags([self::TAG])->remember(self::key($locale), self::TTL, $callback);
        }

        return Cache::remember(self::key($locale), self::TTL, $callback);
    }

    public static function flush(): void
    {
        if (Cache::supportsTags()) {
            Cache::tags([self::TAG])->flush();

            return;
        }

        foreach (self::supportedLocales() as $locale) {
            Cache::forget(self::key($locale));
        }
    }
}
