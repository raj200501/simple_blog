<?php

declare(strict_types=1);

namespace SimpleBlog\Tests\Framework;

use ReflectionClass;
use RuntimeException;

abstract class TestCase
{
    private TestResult $result;

    public function run(TestResult $result): void
    {
        $this->result = $result;
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getMethods() as $method) {
            if (!str_starts_with($method->getName(), 'test')) {
                continue;
            }

            $this->setUp();
            $result->recordTest();

            try {
                $method->invoke($this);
            } catch (RuntimeException $exception) {
                $result->addFailure($reflection->getName() . '::' . $method->getName() . ' - ' . $exception->getMessage());
            }

            $this->tearDown();
        }
    }

    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    protected function assertSame(mixed $expected, mixed $actual, string $message = ''): void
    {
        $this->result->recordAssertion();
        if ($expected !== $actual) {
            $this->fail($message !== '' ? $message : sprintf('Expected %s, got %s', var_export($expected, true), var_export($actual, true)));
        }
    }

    protected function assertTrue(bool $value, string $message = ''): void
    {
        $this->result->recordAssertion();
        if ($value !== true) {
            $this->fail($message !== '' ? $message : 'Expected true.');
        }
    }

    protected function assertFalse(bool $value, string $message = ''): void
    {
        $this->result->recordAssertion();
        if ($value !== false) {
            $this->fail($message !== '' ? $message : 'Expected false.');
        }
    }

    protected function assertNotEmpty(mixed $value, string $message = ''): void
    {
        $this->result->recordAssertion();
        if (empty($value)) {
            $this->fail($message !== '' ? $message : 'Expected value to be non-empty.');
        }
    }

    protected function assertCount(int $expected, array $value, string $message = ''): void
    {
        $this->result->recordAssertion();
        if (count($value) !== $expected) {
            $this->fail($message !== '' ? $message : sprintf('Expected count %d, got %d', $expected, count($value)));
        }
    }

    protected function assertArrayHasKey(string $key, array $value, string $message = ''): void
    {
        $this->result->recordAssertion();
        if (!array_key_exists($key, $value)) {
            $this->fail($message !== '' ? $message : sprintf('Expected array to have key %s', $key));
        }
    }

    private function fail(string $message): void
    {
        throw new RuntimeException($message);
    }
}
