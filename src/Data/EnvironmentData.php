<?php

namespace Beaverlabs\GG\Data;

use Beaverlabs\GG\Data;

class EnvironmentData extends Data
{
    /** @var ?string */
    public $host;

    /** @var ?int */
    public $port;

    public function getEndpoint(): string
    {
        return "http://{$this->host}:{$this->port}";
    }
}
