<?php

declare(strict_types=1);

namespace SimpleBlog\Database;

use PDO;

final class Migrator
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function migrate(): void
    {
        $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $idColumn = $driver === 'mysql'
            ? 'id INT AUTO_INCREMENT PRIMARY KEY'
            : 'id INTEGER PRIMARY KEY AUTOINCREMENT';

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS posts ('
            . $idColumn . ','
            . 'title VARCHAR(255) NOT NULL,'
            . 'content TEXT NOT NULL,'
            . 'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
            . ')'
        );

        $this->ensureIndex('posts', 'posts_created_at_idx', ['created_at']);
    }

    /**
     * @param string[] $columns
     */
    private function ensureIndex(string $table, string $indexName, array $columns): void
    {
        $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'mysql') {
            $statement = $this->pdo->prepare(
                'SELECT COUNT(1) FROM information_schema.statistics '
                . 'WHERE table_schema = DATABASE() AND table_name = :table AND index_name = :index'
            );
            $statement->execute(['table' => $table, 'index' => $indexName]);
            $exists = (int) $statement->fetchColumn() > 0;
            if ($exists) {
                return;
            }
        }

        $columnsSql = implode(', ', $columns);
        $sql = $driver === 'sqlite'
            ? sprintf('CREATE INDEX IF NOT EXISTS %s ON %s(%s)', $indexName, $table, $columnsSql)
            : sprintf('CREATE INDEX %s ON %s(%s)', $indexName, $table, $columnsSql);

        $this->pdo->exec($sql);
    }
}
