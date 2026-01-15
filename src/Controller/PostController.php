<?php

declare(strict_types=1);

namespace SimpleBlog\Controller;

use PDO;
use SimpleBlog\Model\Post;

final class PostController
{
    public function __construct(private readonly PDO $conn)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function index(): array
    {
        return Post::all($this->conn);
    }

    public function create(string $title, string $content): void
    {
        Post::create($this->conn, $title, $content);
    }

    /**
     * @return array<string, mixed>
     */
    public function edit(int $id): array
    {
        return Post::find($this->conn, $id);
    }

    public function update(int $id, string $title, string $content): void
    {
        Post::update($this->conn, $id, $title, $content);
    }

    public function delete(int $id): void
    {
        Post::delete($this->conn, $id);
    }

    /**
     * @return array<string, mixed>
     */
    public function view(int $id): array
    {
        return Post::find($this->conn, $id);
    }
}
