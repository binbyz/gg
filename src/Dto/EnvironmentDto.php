<?php

namespace Beaverlabs\GG\Dto;

use Beaverlabs\GG\Data;

class EnvironmentDto extends Data
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
