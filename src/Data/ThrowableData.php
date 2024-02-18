<?php

namespace Beaverlabs\Gg\Data;

use Beaverlabs\Gg\Data;

class ThrowableData extends Data
{
    public string $message;
    public int $code;
    public string $file;
    public int $line;
    public ?string $previous;
}
