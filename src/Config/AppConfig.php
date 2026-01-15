<?php

declare(strict_types=1);

namespace SimpleBlog\Config;

final class AppConfig
{
    public function __construct(
        public readonly string $environment,
        public readonly string $appName,
        public readonly string $baseUrl,
        public readonly string $dbDriver,
        public readonly string $dbHost,
        public readonly int $dbPort,
        public readonly string $dbName,
        public readonly string $dbUser,
        public readonly string $dbPassword,
        public readonly string $sqlitePath,
    ) {
    }

    public static function fromEnvironment(): self
    {
        $environment = self::env('APP_ENV', 'development');
        $appName = self::env('APP_NAME', 'Simple Blog');
        $baseUrl = rtrim(self::env('APP_BASE_URL', 'http://127.0.0.1:8000'), '/');
        $dbDriver = self::env('DB_DRIVER', 'sqlite');
        $dbHost = self::env('DB_HOST', '127.0.0.1');
        $dbPort = (int) self::env('DB_PORT', '3306');
        $dbName = self::env('DB_NAME', 'simple_blog');
        $dbUser = self::env('DB_USER', 'simple_blog');
        $dbPassword = self::env('DB_PASSWORD', '');
        $sqlitePath = self::env('SQLITE_PATH', __DIR__ . '/../../var/simple_blog.sqlite');

        return new self(
            $environment,
            $appName,
            $baseUrl,
            $dbDriver,
            $dbHost,
            $dbPort,
            $dbName,
            $dbUser,
            $dbPassword,
            $sqlitePath,
        );
    }

    private static function env(string $key, string $default): string
    {
        $value = getenv($key);
        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }
}
