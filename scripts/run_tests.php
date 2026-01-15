<?php

declare(strict_types=1);

use SimpleBlog\Config\EnvLoader;
use SimpleBlog\Support\Autoloader;
use SimpleBlog\Tests\Framework\TestRunner;

$root = dirname(__DIR__);
require_once $root . '/src/Support/Autoloader.php';

Autoloader::register($root);

$loader = new EnvLoader();
$loader->load($root . '/.env');

$runner = new TestRunner($root);
exit($runner->run());
