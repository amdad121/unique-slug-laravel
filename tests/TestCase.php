<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug\Tests;

use AmdadulHaq\UniqueSlug\UniqueSlugServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            UniqueSlugServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
