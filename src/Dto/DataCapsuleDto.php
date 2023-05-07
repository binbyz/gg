<?php

namespace Beaverlabs\GG\Dto;

use Beaverlabs\GG\Data;

class DataCapsuleDto extends Data
{
    /** @var string 'array', 'string', 'integer', 'boolean', 'object', 'NULL' */
    public $type;

    /** @var bool */
    public $isScalarType;

    /** @var ?string */
    public $namespace = null;

    /** @var ?string */
    public $className = null;

    /** @var mixed */
    public $value;
}
