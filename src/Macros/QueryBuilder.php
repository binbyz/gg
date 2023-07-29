<?php

namespace Beaverlabs\Gg\Macros;

use Beaverlabs\Gg\Contracts\Macro;

class QueryBuilder implements Macro
{
    public function register(): \Closure
    {
        return function () {
            /** @var \Illuminate\Database\Query\Builder $this */
            \gg($this->toSql());

            return $this;
        };
    }
}
