<?php

declare(strict_types=1);

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Config\EnvLoader;
use SimpleBlog\Database\Connection;
use SimpleBlog\Repository\PostRepository;
use SimpleBlog\Support\Autoloader;

$root = dirname(__DIR__);
require_once $root . '/src/Support/Autoloader.php';

Autoloader::register($root);

$loader = new EnvLoader();
$loader->load($root . '/.env');

$config = AppConfig::fromEnvironment();
$connection = new Connection($config);
$pdo = $connection->connect();

$repository = new PostRepository($pdo);
$repository->create('Welcome to Simple Blog', 'This is your first post. Feel free to edit or delete it.');
$repository->create('SQLite & MySQL Ready', 'The app ships with a SQLite default and supports MySQL via configuration.');

echo "Seeded sample posts." . PHP_EOL;
