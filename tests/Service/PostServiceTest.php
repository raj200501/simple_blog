<?php

declare(strict_types=1);

namespace SimpleBlog\Tests\Service;

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Database\Connection;
use SimpleBlog\Repository\PostRepository;
use SimpleBlog\Service\PostService;
use SimpleBlog\Tests\TestCase;

final class PostServiceTest extends TestCase
{
    public function testValidationRejectsEmptyTitle(): void
    {
        $config = AppConfig::fromEnvironment();
        $connection = new Connection($config);
        $pdo = $connection->connect();
        $service = new PostService(new PostRepository($pdo));

        $result = $service->create('', 'Content');

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('title', $result['errors']);
    }

    public function testCreateSuccess(): void
    {
        $config = AppConfig::fromEnvironment();
        $connection = new Connection($config);
        $pdo = $connection->connect();
        $service = new PostService(new PostRepository($pdo));

        $result = $service->create('Title', 'Content');

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['id']);
    }
}
