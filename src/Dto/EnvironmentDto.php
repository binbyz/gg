<?php

namespace Beaverlabs\Gg\Dto;

use Beaverlabs\Gg\Data;

class EnvironmentDto extends Data
{
    public ?string $host;

    public ?int $port;

    public function getEndpoint(): string
    {
        return "http://{$this->host}:{$this->port}";
    }
}
