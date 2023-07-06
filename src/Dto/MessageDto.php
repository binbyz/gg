<?php

namespace Beaverlabs\Gg\Dto;

use Beaverlabs\Gg\Data;

class MessageDto extends Data
{
    public string $type;
    public string $language;
    public string $version;
    public string $framework;

    /** @var mixed */
    public $data;

    public array $trace;
}
