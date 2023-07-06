<?php

namespace Beaverlabs\Gg\Dto;

use Beaverlabs\Gg\Data;

class DataCapsuleDto extends Data
{
    /** @var string array, string, integer, boolean, object, NULL */
    public string $type;

    public bool $isScalar;
    public ?string $namespace = null;
    public ?string $class = null;
    public ?bool $pruned = false;

    /** @var mixed */
    public $value;
}
