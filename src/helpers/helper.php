<?php

use Beaverlabs\Gg\Gg;

if (! function_exists('gg')) {
    function gg(...$parameters): Gg
    {
        $gg = Gg::getInstance();

        if (! count($parameters)) {
            return $gg;
        }

        return $gg->send($parameters);
    }

    function gd(...$parameters): Gg
    {
        gg($parameters);

        exit();
    }
}
