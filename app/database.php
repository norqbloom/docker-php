<?php

declare(strict_types=1);

$database = new PDO('sqlite:/var/lib/app/app.sqlite', null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_TIMEOUT => 10,
]);
$database->exec('PRAGMA foreign_keys = ON');

return $database;
