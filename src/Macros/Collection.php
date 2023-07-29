<?php

namespace Beaverlabs\Gg\Macros;

use Beaverlabs\Gg\Contracts\Macro;

class Collection implements Macro
{
    public function register(): \Closure
    {
        return function () {
            /** @var \Illuminate\Support\Collection $this */
            \gg($this->items);

            return $this;
        };
    }
}
