<?php

/**
 * PHP 8.5 deprecates PDO::MYSQL_ATTR_SSL_CA; patch Laravel's default database config after composer install/update.
 */

$path = __DIR__.'/../vendor/laravel/framework/config/database.php';

if (! is_file($path)) {
    exit(0);
}

$contents = file_get_contents($path);

if (str_contains($contents, 'PdoMysql::ATTR_SSL_CA')) {
    exit(0);
}

$contents = preg_replace(
    '/^use Illuminate\\\\Support\\\\Str;$/m',
    "use Illuminate\\Support\\Str;\nuse Pdo\\Mysql as PdoMysql;",
    $contents,
    1,
    $useCount
);

if ($useCount === 0) {
    fwrite(STDERR, "Could not patch {$path}: use statement not found.\n");
    exit(1);
}

$replacement = "(defined('Pdo\\Mysql::ATTR_SSL_CA') ? PdoMysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA) => env('MYSQL_ATTR_SSL_CA'),";
$patched = preg_replace(
    '/PDO::MYSQL_ATTR_SSL_CA => env\(\'MYSQL_ATTR_SSL_CA\'\),/',
    $replacement,
    $contents,
    -1,
    $count
);

if ($count < 1) {
    fwrite(STDERR, "Could not patch {$path}: PDO::MYSQL_ATTR_SSL_CA not found.\n");
    exit(1);
}

file_put_contents($path, $patched);
