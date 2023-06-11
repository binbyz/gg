<?php

namespace Beaverlabs\Gg\Dto;

use Beaverlabs\Gg\Data;

class MessageDto extends Data
{
    public string $messageType;
    public string $language;
    public string $version;
    public string $framework;

    /** @var mixed */
    public $data;

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
