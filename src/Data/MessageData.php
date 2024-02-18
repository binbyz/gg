<?php

namespace Beaverlabs\Gg\Data;

use Beaverlabs\Gg\Data;
use Beaverlabs\Gg\Enums\MessageType;

class MessageData extends Data
{
    public MessageType $type;
    public string $language;
    public string $version;
    public string $framework;

    public mixed $data;

    public array $trace;
}
