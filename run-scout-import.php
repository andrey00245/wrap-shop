<?php
/**
 * Одноразовый скрипт: запуск scout:import через CLI (обход веб-кеша).
 * Класть в public/ и открывать: https://wrap.shop/run-scout-import.php
 * После выполнения — удали этот файл.
 */

// Для ~1500+ записей: убираем лимит времени и даём больше памяти
set_time_limit(0);
ini_set('memory_limit', '512M');

$root = dirname(__DIR__);
if (!is_file($root . '/composer.json')) {
    $root = __DIR__;
}
chdir($root);

// Чтобы Composer/Artisan не ругались на HOME
$home = $root . '/.composer-home';
if (!is_dir($home)) {
    mkdir($home, 0755, true);
}
putenv('HOME=' . $home);
putenv('COMPOSER_HOME=' . $home);

header('Content-Type: text/html; charset=utf-8');
echo "<pre>\n";

$model = isset($_GET['model']) ? $_GET['model'] : 'App\\Models\\Product';

echo "Запуск: php artisan scout:import \"{$model}\"\n\n";
passthru('php artisan scout:import "' . str_replace('"', '\\"', $model) . '" 2>&1');

echo "\n</pre>";
echo "<p>Готово. Удали этот файл (run-scout-import.php) с сервера.</p>";
