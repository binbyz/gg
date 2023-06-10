<?php

namespace Beaverlabs\Gg\Dto;

use Beaverlabs\Gg\Data;

class DataCapsuleDto extends Data
{
    /** @var string array, string, integer, boolean, object, NULL */
    public string $type;

    public bool $isScalarType;

    public ?string $namespace = null;

    public ?string $className = null;

    public ?bool $pruned = false;

    /** @var mixed */
    public $value;
}
