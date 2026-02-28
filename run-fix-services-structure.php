<?php
/**
 * Одноразовый скрипт: перенос app/Services/Services/* в app/Services/ (исправление PSR-4).
 * Класть в public/ и открывать: https://wrap.shop/run-fix-services-structure.php
 * После выполнения — удали этот файл.
 */

$root = dirname(__DIR__);
if (!is_file($root . '/composer.json')) {
    $root = __DIR__;
}

$from = $root . '/app/Services/Services';
$to   = $root . '/app/Services';

header('Content-Type: text/html; charset=utf-8');
echo "<pre>\n";

if (!is_dir($from)) {
    echo "Папка app/Services/Services/ не найдена — ничего переносить не нужно.\n";
    echo "</pre>";
    exit;
}

$moved = 0;
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($from, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST
);

// Сначала создаём нужные папки в app/Services/
foreach ($files as $path) {
    if ($path->isDir()) {
        $rel = substr($path->getPathname(), strlen($from) + 1);
        $targetDir = $to . '/' . $rel;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
            echo "Создана папка: app/Services/{$rel}\n";
        }
    }
}

// Переносим файлы
foreach (new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($from, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
) as $path) {
    $rel = substr($path->getPathname(), strlen($from) + 1);
    $target = $to . '/' . $rel;
    if ($path->isFile()) {
        if (!is_file($target) || filemtime($path->getPathname()) > filemtime($target)) {
            $dir = dirname($target);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            copy($path->getPathname(), $target);
            echo "Скопирован: app/Services/{$rel}\n";
            $moved++;
        }
    }
}

// Удаляем старую папку Services/Services
function removeDir($dir) {
    if (!is_dir($dir)) return;
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $p = $dir . '/' . $f;
        is_dir($p) ? removeDir($p) : unlink($p);
    }
    rmdir($dir);
}
removeDir($from);
echo "\nУдалена папка app/Services/Services/\n";
echo "Перенесено файлов: {$moved}\n";
echo "\nДальше открой run-composer-dump.php или выполни в Command Runner: php artisan optimize:clear\n";
echo "</pre>";
echo "<p>Удали этот файл (run-fix-services-structure.php) с сервера.</p>";
