<?php

namespace Beaverlabs\Gg\Exceptions;

class ValueTypeException extends \Exception
{
    public static function make($value): self
    {
        return new static("The value type of {$value} is not supported.");
    }
}
