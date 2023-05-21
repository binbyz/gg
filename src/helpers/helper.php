<?php

use Beaverlabs\GG\Exceptions\ValueTypeException;
use Beaverlabs\GG\GG;

if (! function_exists('gg')) {
    /**
     * @throws ReflectionException
     * @throws ValueTypeException
     */
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
