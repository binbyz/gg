<?php

use Beaverlabs\GG\GG;

if (! function_exists('gg')) {
    function gg(...$parameters)
    {
        if (! count($parameters)) {
            return GG::getInstance();
        }

        $gg = GG::getInstance();

        $gg->send($parameters);
    }
}
