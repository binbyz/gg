<?php

namespace Beaverlabs\Gg\Tests;

use Beaverlabs\Gg\GgServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use \Orchestra\Testbench\Concerns\CreatesApplication;
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(GgServiceProvider::class);
    }
}
