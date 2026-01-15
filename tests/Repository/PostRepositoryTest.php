<?php

declare(strict_types=1);

namespace SimpleBlog\Tests\Repository;

use SimpleBlog\Config\AppConfig;
use SimpleBlog\Database\Connection;
use SimpleBlog\Repository\PostRepository;
use SimpleBlog\Tests\TestCase;

final class PostRepositoryTest extends TestCase
{
    public function testCreateAndFind(): void
    {
        $config = AppConfig::fromEnvironment();
        $connection = new Connection($config);
        $pdo = $connection->connect();

        $repository = new PostRepository($pdo);
        $id = $repository->create('Hello', 'Content');

        $post = $repository->find($id);

        $this->assertSame('Hello', $post['title']);
        $this->assertSame('Content', $post['content']);
    }

    public function testUpdate(): void
    {
        $config = AppConfig::fromEnvironment();
        $connection = new Connection($config);
        $pdo = $connection->connect();

        $repository = new PostRepository($pdo);
        $id = $repository->create('Before', 'Before content');

        $repository->update($id, 'After', 'After content');
        $post = $repository->find($id);

        $this->assertSame('After', $post['title']);
        $this->assertSame('After content', $post['content']);
    }

    public function testDelete(): void
    {
        $config = AppConfig::fromEnvironment();
        $connection = new Connection($config);
        $pdo = $connection->connect();

        $repository = new PostRepository($pdo);
        $id = $repository->create('Delete', 'Delete content');
        $repository->delete($id);

        $posts = $repository->all();

        $this->assertCount(0, $posts);
    }
}
