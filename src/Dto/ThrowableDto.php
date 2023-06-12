<?php

namespace Beaverlabs\Gg\Dto;

use Beaverlabs\Gg\Data;

class ThrowableDto extends Data
{
    public string $message;

    public int $code;

    public string $file;

    public int $line;

    public array $trace;

    public ?string $previous;
}
