<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Traits\MakeTrait;

class GgConnection
{
    use MakeTrait;

    public string $host;
    public int $port;

    public function __construct(string $host = 'host.docker.internal', int $port = 21868)
    {
        $this->host = \config('gg.host') ?: $host;
        $this->port = \config('gg.port') ?: $port;
    }
}
