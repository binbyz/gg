<?php

namespace Beaverlabs\GG\Dto;

use Beaverlabs\GG\Data;

class MessageDto extends Data
{
    /** @var string */
    public $language;

    /** @var string */
    public $version;

    /** @var string */
    public $framework;

    /** @var mixed */
    public $data;

    /** @var array */
    public $backtrace;

    public function __construct(
        string $language,
        string $framework,
        $data,
        array $backtrace
    ) {
        $this->language = $language;
        $this->framework = $framework;
        $this->data = $data;
        $this->backtrace = $backtrace;
    }
}
