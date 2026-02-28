<?php
/**
 * Одноразовый скрипт: composer install на сервере (когда нет SSH).
 * Класть в public/ и открывать: https://wrap.shop/run-composer-install.php
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

passthru('composer install --no-interaction 2>&1');
