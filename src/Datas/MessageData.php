<?php

namespace Beaverlabs\Gg\Datas;

use Beaverlabs\Gg\Data;

class MessageData extends Data
{
    public string $type;
    public string $language;
    public string $version;
    public string $framework;

    /** @var mixed */
    public $data;

    public array $trace;
}
