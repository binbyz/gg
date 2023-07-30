<?php

namespace Beaverlabs\Gg\Listeners;

use Illuminate\Log\Events\MessageLogged;

class ExceptionListener
{
    public function handle(MessageLogged $logged): void
    {
        if (\array_key_exists('exception', $logged->context) && $logged->context['exception'] instanceof \Throwable) {
            \gtrace($logged->context['exception']);
        }
    }
}
