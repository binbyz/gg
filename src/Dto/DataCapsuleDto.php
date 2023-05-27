<?php

namespace Beaverlabs\GG\Dto;

use Beaverlabs\GG\Data;

class DataCapsuleDto extends Data
{
    /** @var string array, string, integer, boolean, object, NULL */
    public string $type;

    public bool $isScalarType;

    public ?string $namespace = null;

    public ?string $className = null;

    /** @var mixed */
    public $value;
}
