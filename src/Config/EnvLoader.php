<?php

declare(strict_types=1);

namespace SimpleBlog\Config;

use RuntimeException;

final class EnvLoader
{
    private const COMMENT_CHARS = ['#', ';'];

    /**
     * @return array<string, string>
     */
    public function load(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            throw new RuntimeException(sprintf('Unable to read env file: %s', $path));
        }

        $variables = [];
        foreach ($lines as $line) {
            $parsed = $this->parseLine($line);
            if ($parsed === null) {
                continue;
            }
            [$key, $value] = $parsed;
            $variables[$key] = $value;
            if (getenv($key) === false) {
                putenv($key . '=' . $value);
            }
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        return $variables;
    }

    /**
     * @return array{string, string}|null
     */
    private function parseLine(string $line): ?array
    {
        $trimmed = trim($line);
        if ($trimmed === '') {
            return null;
        }

        if ($this->isComment($trimmed)) {
            return null;
        }

        $position = strpos($trimmed, '=');
        if ($position === false) {
            throw new RuntimeException(sprintf('Invalid env line: %s', $line));
        }

        $key = trim(substr($trimmed, 0, $position));
        $value = trim(substr($trimmed, $position + 1));

        if ($key === '') {
            throw new RuntimeException(sprintf('Empty env key in line: %s', $line));
        }

        $value = $this->stripQuotes($value);

        return [$key, $value];
    }

    private function isComment(string $line): bool
    {
        foreach (self::COMMENT_CHARS as $char) {
            if (str_starts_with($line, $char)) {
                return true;
            }
        }

        return false;
    }

    private function stripQuotes(string $value): string
    {
        if ($value === '') {
            return $value;
        }

        $first = $value[0];
        $last = $value[strlen($value) - 1];
        if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
