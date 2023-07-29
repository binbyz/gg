<?php

namespace Beaverlabs\Gg\Macros;

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
