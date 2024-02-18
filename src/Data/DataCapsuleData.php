<?php

namespace Beaverlabs\Gg\Data;

use Beaverlabs\Gg\Data;

class DataCapsuleData extends Data
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
