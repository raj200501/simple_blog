<?php

declare(strict_types=1);

namespace SimpleBlog\Database;

use PDO;
use PDOException;
use RuntimeException;
use SimpleBlog\Config\AppConfig;

final class Connection
{
    public function __construct(private readonly AppConfig $config)
    {
    }

    public function connect(): PDO
    {
        $dsn = $this->buildDsn();

        try {
            $pdo = new PDO($dsn, $this->config->dbUser, $this->config->dbPassword, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                sprintf('Database connection failed using %s: %s', $dsn, $exception->getMessage()),
                (int) $exception->getCode(),
                $exception
            );
        }

        return $pdo;
    }

    private function buildDsn(): string
    {
        if ($this->config->dbDriver === 'sqlite') {
            return sprintf('sqlite:%s', $this->config->sqlitePath);
        }

        return sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $this->config->dbHost,
            $this->config->dbPort,
            $this->config->dbName
        );
    }
}
