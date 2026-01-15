<?php

declare(strict_types=1);

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Config\EnvLoader;
use SimpleBlog\Database\Connection;
use SimpleBlog\Support\Autoloader;

$root = dirname(__DIR__);
require_once $root . '/src/Support/Autoloader.php';

Autoloader::register($root);

$envLoader = new EnvLoader();
$envLoader->load($root . '/.env');

$appConfig = AppConfig::fromEnvironment();
$connection = new Connection($appConfig);
$conn = $connection->connect();

return $conn;
