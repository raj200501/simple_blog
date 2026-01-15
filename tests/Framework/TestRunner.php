<?php

declare(strict_types=1);

namespace SimpleBlog\Tests\Framework;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

final class TestRunner
{
    public function __construct(private readonly string $root)
    {
    }

    public function run(): int
    {
        $result = new TestResult();

        foreach ($this->discoverTests() as $className) {
            $test = new $className();
            if (!$test instanceof TestCase) {
                continue;
            }
            $test->run($result);
        }

        $this->report($result);

        return $result->hasFailures() ? 1 : 0;
    }

    /**
     * @return array<int, class-string>
     */
    private function discoverTests(): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->root . '/tests'));
        $regex = new RegexIterator($iterator, '/Test.php$/');

        $classes = [];
        foreach ($regex as $file) {
            require_once $file->getPathname();
        }

        foreach (get_declared_classes() as $className) {
            if (str_starts_with($className, 'SimpleBlog\\Tests\\') && str_ends_with($className, 'Test')) {
                $classes[] = $className;
            }
        }

        sort($classes);

        return $classes;
    }

    private function report(TestResult $result): void
    {
        echo sprintf("Tests: %d, Assertions: %d\n", $result->tests(), $result->assertions());

        if ($result->hasFailures()) {
            echo "Failures:\n";
            foreach ($result->failures() as $failure) {
                echo "- {$failure}\n";
            }
        }
    }
}
