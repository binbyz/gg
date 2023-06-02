<?php

namespace Beaverlabs\Gg\Traits;

trait MakeTrait
{
    public static function make(): self
    {
        return new static();
    }
}
