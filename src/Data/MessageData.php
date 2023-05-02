<?php

namespace Beaverlabs\GG\Data;

use Beaverlabs\GG\Data;

class MessageData extends Data
{
    /** @var string */
    public $language;

    /** @var bool */
    public $isScalaType;

    /** @var string */
    public $framework;

    /** @var mixed */
    public $data;

    public function __construct(
        string $language,
        string $framework,
        $data,
        bool $isScalaType
    ) {
        $this->language = $language;
        $this->framework = $framework;
        $this->data = $data;
        $this->isScalaType = $isScalaType;
    }
}
