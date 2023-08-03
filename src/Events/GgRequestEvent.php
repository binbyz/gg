<?php

namespace Beaverlabs\Gg\Events;

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Events\Dispatchable;

class GgRequestEvent
{
    use Dispatchable;

    private Request $request;
    private float $uniqueId;

    public function __construct(Request $request, float $timestamp)
    {
        $this->request = $request;
        $this->uniqueId = $timestamp;
    }
}
