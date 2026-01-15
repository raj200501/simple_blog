<?php

declare(strict_types=1);

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Config\EnvLoader;
use SimpleBlog\Database\Connection;
use SimpleBlog\Database\Migrator;
use SimpleBlog\Support\Autoloader;

$root = dirname(__DIR__);
require_once $root . '/src/Support/Autoloader.php';

Autoloader::register($root);

$loader = new EnvLoader();
$loader->load($root . '/.env');

$config = AppConfig::fromEnvironment();

if ($config->dbDriver === 'sqlite') {
    $directory = dirname($config->sqlitePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

$connection = new Connection($config);
$pdo = $connection->connect();

$migrator = new Migrator($pdo);
$migrator->migrate();

echo "Database migrated using {$config->dbDriver}." . PHP_EOL;
