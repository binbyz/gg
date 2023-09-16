<?php

namespace Beaverlabs\Gg\Providers;

use Beaverlabs\Gg\Listeners\HttpConnectionFailedListener;
use Beaverlabs\Gg\Listeners\HttpResponseListener;
use Beaverlabs\Gg\Listeners\QueryExecutedListener;
use Beaverlabs\Gg\Listeners\RouteMatchedListener;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Client\Events\ConnectionFailed;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Routing\Events\RouteMatched;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        QueryExecuted::class => [
            QueryExecutedListener::class,
        ],
        MessageLogged::class => [
            \Beaverlabs\Gg\Listeners\ExceptionListener::class,
        ],
        RequestSending::class => [
        ],
        ResponseReceived::class => [
            HttpResponseListener::class,
        ],
        ConnectionFailed::class => [
            HttpConnectionFailedListener::class,
        ],
        RouteMatched::class => [
            RouteMatchedListener::class,
        ],
    ];
}
