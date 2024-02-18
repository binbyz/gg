<?php

namespace Beaverlabs\Gg\Tests;

use Beaverlabs\Gg\Providers\EventServiceProvider;
use Beaverlabs\Gg\Providers\GgServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use DatabaseMigrations;
    use \Orchestra\Testbench\Concerns\CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(GgServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }
}
