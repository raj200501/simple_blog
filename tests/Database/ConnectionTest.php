<?php

declare(strict_types=1);

namespace SimpleBlog\Tests\Database;

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Database\Connection;
use SimpleBlog\Tests\TestCase;

final class ConnectionTest extends TestCase
{
    public function testConnectsToDatabase(): void
    {
        $config = AppConfig::fromEnvironment();
        $connection = new Connection($config);
        $pdo = $connection->connect();

        $result = $pdo->query('SELECT 1');

        $this->assertTrue($result !== false);
        $this->assertSame(1, (int) $result->fetchColumn());
    }
}
