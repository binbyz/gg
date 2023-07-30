<?php

namespace Beaverlabs\Gg\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Log\Events\MessageLogged;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        QueryExecuted::class => [
            \Beaverlabs\Gg\Listeners\QueryExecutedListener::class,
        ],
        MessageLogged::class => [
            \Beaverlabs\Gg\Listeners\ExceptionListener::class,
        ],
    ];
}
