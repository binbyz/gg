<?php

namespace Beaverlabs\Gg\Events;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Events\Dispatchable;

class GgResponseEvent
{
    use Dispatchable;

    private Response $response;
    private float $uniqueId;

    public function __construct(Response $response, float $timestamp)
    {
        $this->response = $response;
        $this->uniqueId = $timestamp;
    }
}
