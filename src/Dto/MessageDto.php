<?php

namespace Beaverlabs\GG\Dto;

use Beaverlabs\GG\Data;

class MessageDto extends Data
{
    public string $language;

    public string $version;

    public string $framework;

    public mixed $data;

    public array $backtrace;

    public function __construct(
        string $language,
        string $framework,
        $data,
        array $backtrace
    ) {
        $this->language = $language;
        $this->framework = $framework;
        $this->data = $data;
        $this->backtrace = $backtrace;
    }
}
