<?php

namespace Beaverlabs\Gg\Contracts;

interface Macro
{
    public function register(): \Closure;
}
