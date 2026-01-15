<?php

declare(strict_types=1);

namespace SimpleBlog\Model;

use PDO;

final class Post
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(PDO $conn): array
    {
        $stmt = $conn->prepare('SELECT * FROM posts ORDER BY created_at DESC, id DESC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(PDO $conn, string $title, string $content): void
    {
        $stmt = $conn->prepare('INSERT INTO posts (title, content) VALUES (:title, :content)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();
    }

    /**
     * @return array<string, mixed>
     */
    public static function find(PDO $conn, int $id): array
    {
        $stmt = $conn->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return (array) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update(PDO $conn, int $id, string $title, string $content): void
    {
        $stmt = $conn->prepare('UPDATE posts SET title = :title, content = :content WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();
    }

    public static function delete(PDO $conn, int $id): void
    {
        $stmt = $conn->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
