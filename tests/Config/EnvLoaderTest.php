<?php

declare(strict_types=1);

namespace SimpleBlog\Tests\Config;

use SimpleBlog\Config\EnvLoader;
use SimpleBlog\Tests\TestCase;

final class EnvLoaderTest extends TestCase
{
    public function testLoadsEnvFile(): void
    {
        $path = sys_get_temp_dir() . '/simple_blog_env_test.env';
        file_put_contents($path, "APP_NAME=Hello Blog\n# Comment\nDB_DRIVER=sqlite\n");

        $loader = new EnvLoader();
        $values = $loader->load($path);

        $this->assertSame('Hello Blog', $values['APP_NAME']);
        $this->assertSame('sqlite', $values['DB_DRIVER']);

        unlink($path);
    }
}
