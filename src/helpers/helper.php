<?php

use Beaverlabs\Gg\GG;

if (! function_exists('gg')) {
    function gg(...$parameters): GG
    {
        $gg = GG::getInstance();

        if (! count($parameters)) {
            return $gg;
        }

        return $gg->send($parameters);
    }

    function gd(...$parameters): GG
    {
        gg($parameters);

        exit();
    }
}
