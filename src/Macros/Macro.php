<?php

namespace Beaverlabs\Gg\Macros;

interface Macro
{
    public function register(): \Closure;
}
