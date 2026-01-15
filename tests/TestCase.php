<?php

declare(strict_types=1);

namespace SimpleBlog\Tests;

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Database\Connection;
use SimpleBlog\Database\Migrator;
use SimpleBlog\Tests\Framework\TestCase as FrameworkTestCase;

abstract class TestCase extends FrameworkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $config = AppConfig::fromEnvironment();
        $this->ensureSqliteDirectory($config->sqlitePath);

        $connection = new Connection($config);
        $pdo = $connection->connect();

        $migrator = new Migrator($pdo);
        $migrator->migrate();

        $pdo->exec('DELETE FROM posts');
    }

    private function ensureSqliteDirectory(string $path): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }
}
