<?php

use Beaverlabs\GG\GG;

if (! function_exists('gg')) {
    function gg(...$parameters): GG
    {
        $gg = GG::getInstance();

        if (! count($parameters)) {
            return $gg;
        }

        return $gg->send($parameters);
    }
}
