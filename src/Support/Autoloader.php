<?php

declare(strict_types=1);

namespace SimpleBlog\Support;

final class Autoloader
{
    public static function register(string $rootDir): void
    {
        spl_autoload_register(static function (string $class) use ($rootDir): void {
            $prefixes = [
                'SimpleBlog\\Tests\\' => $rootDir . '/tests/',
                'SimpleBlog\\' => $rootDir . '/src/',
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                if (str_starts_with($class, $prefix)) {
                    $relative = substr($class, strlen($prefix));
                    $path = $baseDir . str_replace('\\', '/', $relative) . '.php';
                    if (is_file($path)) {
                        require_once $path;
                    }
                    return;
                }
            }
        });
    }
}
