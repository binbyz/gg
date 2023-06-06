<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Traits\MakeTrait;

class GgConnection
{
    use MakeTrait;

    public string $host;
    public int $port;

    public function __construct(string $host = 'localhost', int $port = 21868)
    {
        $this->host = \getenv('GG_HOST') ?: $host;
        $this->port = \getenv('GG_PORT') ?: $port;
    }
}
