<?php

namespace Beaverlabs\Gg\Macros;

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
