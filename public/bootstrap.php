<?php

declare(strict_types=1);

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Config\EnvLoader;
use SimpleBlog\Database\Connection;
use SimpleBlog\Repository\PostRepository;
use SimpleBlog\Service\PostService;
use SimpleBlog\Support\Autoloader;

$root = dirname(__DIR__);
require_once $root . '/src/Support/Autoloader.php';

Autoloader::register($root);

$envLoader = new EnvLoader();
$envLoader->load($root . '/.env');

$config = AppConfig::fromEnvironment();

if ($config->dbDriver === 'sqlite') {
    $directory = dirname($config->sqlitePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

$connection = new Connection($config);
$pdo = $connection->connect();

$postRepository = new PostRepository($pdo);
$postService = new PostService($postRepository);

return [
    'config' => $config,
    'pdo' => $pdo,
    'postService' => $postService,
];
