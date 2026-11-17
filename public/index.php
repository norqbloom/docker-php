<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=UTF-8');

echo 'Apache + PHP-FPM が動作しています。', PHP_EOL;
echo 'PHP version: ', PHP_VERSION, PHP_EOL;
echo 'SAPI: ', PHP_SAPI, PHP_EOL;

/** @var PDO $database */
$database = require '/var/www/app/database.php';
echo 'SQLite version: ', $database->query('SELECT sqlite_version()')->fetchColumn(), PHP_EOL;
