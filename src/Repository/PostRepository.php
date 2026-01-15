<?php

declare(strict_types=1);

namespace SimpleBlog\Repository;

use PDO;
use RuntimeException;

final class PostRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $statement = $this->pdo->query('SELECT * FROM posts ORDER BY created_at DESC, id DESC');
        if ($statement === false) {
            throw new RuntimeException('Failed to fetch posts');
        }

        return $statement->fetchAll();
    }

    public function create(string $title, string $content): int
    {
        $statement = $this->pdo->prepare('INSERT INTO posts (title, content) VALUES (:title, :content)');
        if ($statement === false) {
            throw new RuntimeException('Failed to prepare insert statement');
        }

        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @return array<string, mixed>
     */
    public function find(int $id): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id');
        if ($statement === false) {
            throw new RuntimeException('Failed to prepare select statement');
        }

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $post = $statement->fetch();

        if ($post === false) {
            throw new RuntimeException(sprintf('Post not found: %d', $id));
        }

        return $post;
    }

    public function update(int $id, string $title, string $content): void
    {
        $statement = $this->pdo->prepare('UPDATE posts SET title = :title, content = :content WHERE id = :id');
        if ($statement === false) {
            throw new RuntimeException('Failed to prepare update statement');
        }

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->execute();

        if ($statement->rowCount() === 0) {
            throw new RuntimeException(sprintf('Post not found for update: %d', $id));
        }
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');
        if ($statement === false) {
            throw new RuntimeException('Failed to prepare delete statement');
        }

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}
