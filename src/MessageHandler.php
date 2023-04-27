<?php

namespace Beaverlabs\GG;

class MessageHandler implements \JsonSerializable
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

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
