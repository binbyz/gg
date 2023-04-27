<?php

namespace Beaverlabs\GG;

class MessageConverter
{
    /** @var mixed */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function convert($data): self
    {
        return new self($data);
    }

    public function isScalarType(): bool
    {
        return is_scalar($this->data) || is_null($this->data);
    }
}
