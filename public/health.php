<?php

declare(strict_types=1);

$container = require __DIR__ . '/bootstrap.php';
$config = $container['config'];

header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'environment' => $config->environment,
    'driver' => $config->dbDriver,
]);
