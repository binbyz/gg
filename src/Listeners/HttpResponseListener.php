<?php

namespace Beaverlabs\Gg\Listeners;

use Illuminate\Http\Client\Events\ResponseReceived;

class HttpResponseListener
{
    public function handle(ResponseReceived $responseReceived): void
    {
        \gg($responseReceived);
    }
}
