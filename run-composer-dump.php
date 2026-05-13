<?php
/**
 * Одноразовый скрипт: обновляет autoload на сервере (когда нет SSH).
 * После выполнения — удали этот файл.
 */
chdir(__DIR__);
passthru('composer dump-autoload --no-interaction 2>&1');
