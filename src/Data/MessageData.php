<?php

namespace Beaverlabs\GG\Data;

use Beaverlabs\GG\Data;

class MessageData extends Data
{
    /** @var string */
    public $language;

    /** @var string */
    public $version;

    /** @var bool */
    public $isScalarType;

    /** @var string */
    public $type;

    /** @var string */
    public $framework;

    /** @var mixed */
    public $data;

    /** @var array */
    public $backtrace;

    public function __construct(
        string $language,
        string $framework,
        bool $isScalaType,
        string $type,
        $data,
        array $backtrace
    ) {
        $this->language = $language;
        $this->framework = $framework;
        $this->isScalarType = $isScalaType;
        $this->type = $type;
        $this->data = $data;
        $this->backtrace = $backtrace;
    }
}
