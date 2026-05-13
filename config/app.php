<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'Europe/Kiev',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'google_map_key' => env('GOOGLE_MAP_KEY', ''),

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    'my_store' => [
        'username' => env('MOY_SKLAD_USERNAME', ''),
        'password' => env('MOY_SKLAD_PASSWORD', ''),
        /** Bearer-токен JSON API (лише для тестового роута /moysklad/bearer-sync-one-product) */
        'token' => env('MOY_SKLAD_TOKEN', ''),
        /** ID товару в БД для тесту синку без product_id в URL (напр. MOY_SKLAD_BEARER_TEST_PRODUCT_ID=333) */
        'bearer_test_product_id' => env('MOY_SKLAD_BEARER_TEST_PRODUCT_ID'),
    ],

    /** Query `token` для /moysklad/bearer-sync-one-product (можна окремо від cron) */
    'moysklad_bearer_sync_secret' => env('MOY_SKLAD_BEARER_SYNC_SECRET', ''),

    /** Розмір батчу для джоб синку цін/залишків (products:dispatch-price-sync-jobs) */
    'schedule_price_sync_batch' => max(1, min(500, (int) env('SCHEDULE_PRICE_SYNC_BATCH', 100))),

    /**
     * Обмеження навантаження на JSON API МойСклад (ціни/залишки, синк category_id, інші GET remap).
     *
     * @see https://dev.moysklad.ru/doc/api/remap/1.2/#mojsklad-json-api-ogranicheniq
     */
    'moysklad_remap' => [
        'delay_ms_between_requests' => max(0, min(5000, (int) env('MOY_SKLAD_REMAP_DELAY_MS', 120))),
        'timeout_seconds' => max(10, min(180, (int) env('MOY_SKLAD_REMAP_TIMEOUT', 90))),
        'max_retries_429' => max(0, min(30, (int) env('MOY_SKLAD_429_MAX_RETRIES', 8))),
        'retry_backoff_base_seconds' => max(1, min(60, (int) env('MOY_SKLAD_429_BACKOFF_BASE', 3))),
    ],

    /** Сторінка пошуку та превью: скільки hits забирати з Algolia за один запит (макс. 50) */
    'algolia_search_max_hits' => max(1, min(50, (int) env('ALGOLIA_SEARCH_MAX_HITS', 50))),

    /**
     * Algolia typoTolerance для каталогу: strict (дефолт, менше «чужих» карток), min, true, false.
     *
     * @see https://www.algolia.com/doc/api-reference/api-parameters/typoTolerance/
     */
    'algolia_search_typo_tolerance' => env('ALGOLIA_SEARCH_TYPO_TOLERANCE', 'strict'),
];
