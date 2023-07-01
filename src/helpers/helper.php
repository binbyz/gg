<?php

use Beaverlabs\Gg\Gg;

if (! function_exists('gg')) {
    function gg(...$parameters): Gg
    {
        if (count($parameters)) {
            foreach (current($parameters) as $parameter) {
                Gg::getInstance()->send($parameter);
            }
        }

        return Gg::getInstance();
    }
}

if (! function_exists('gd')) {
    function gd(...$parameters): Gg
    {
        gg($parameters);

        die();
    }
}
