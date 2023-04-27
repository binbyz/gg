<?php

namespace Beaverlabs\GG;

final class GG
{
    /** @var string */
    private $host;

    /** @var int */
    private $port;

    /** @var string */
    private $endpoint;

    public function __construct(string $host = 'localhost', int $port = 21868)
    {
        $this->host = $host;
        $this->port = $port;
        $this->endpoint = sprintf('%s:%d', $host, $port);
    }
}
