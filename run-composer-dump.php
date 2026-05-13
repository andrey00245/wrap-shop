<?php
/**
 * Одноразовый скрипт: обновляет autoload и сбрасывает кеш Laravel (чтобы подхватились scout и др.).
 * Класть в public/ и открывать: https://wrap.shop/run-composer-dump.php
 * После выполнения — удали этот файл.
 */

// Корень проекта (composer.json лежит на уровень выше public/)
$root = dirname(__DIR__);
if (!is_file($root . '/composer.json')) {
    $root = __DIR__; // если скрипт в корне проекта
}
chdir($root);

$home = $root . '/.composer-home';
if (!is_dir($home)) {
    mkdir($home, 0755, true);
}
putenv('HOME=' . $home);
putenv('COMPOSER_HOME=' . $home);

echo "<pre>\n";

// 1. Очистка кеша Laravel (чтобы scout:import и др. команды перерегистрировались)
$cacheDir = $root . '/bootstrap/cache';
$cleared = [];
foreach (['packages.php', 'services.php', 'config.php'] as $file) {
    $path = $cacheDir . '/' . $file;
    if (is_file($path) && @unlink($path)) {
        $cleared[] = $file;
    }
}
if (!empty($cleared)) {
    echo "Очищен кеш Laravel: " . implode(', ', $cleared) . "\n\n";
}

// 2. Composer dump-autoload
passthru('composer dump-autoload --no-interaction 2>&1');

echo "\n</pre>";
echo "<p>Готово. Дальше в Command Runner выполни: <code>scout:import \"App\\Models\\Product\"</code></p>";
