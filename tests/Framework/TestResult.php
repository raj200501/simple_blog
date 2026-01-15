<?php

declare(strict_types=1);

namespace SimpleBlog\Tests\Framework;

final class TestResult
{
    /** @var array<int, string> */
    private array $failures = [];

    private int $tests = 0;
    private int $assertions = 0;

    public function recordTest(): void
    {
        $this->tests++;
    }

    public function recordAssertion(): void
    {
        $this->assertions++;
    }

    public function addFailure(string $message): void
    {
        $this->failures[] = $message;
    }

    public function tests(): int
    {
        return $this->tests;
    }

    public function assertions(): int
    {
        return $this->assertions;
    }

    /**
     * @return array<int, string>
     */
    public function failures(): array
    {
        return $this->failures;
    }

    public function hasFailures(): bool
    {
        return $this->failures !== [];
    }
}
