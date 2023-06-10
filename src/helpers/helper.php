<?php

use Beaverlabs\Gg\Exceptions\ValueTypeException;
use Beaverlabs\Gg\Gg;

if (! function_exists('gg')) {
    /**
     * @throws ReflectionException
     * @throws ValueTypeException
     */
    function gg(...$parameters): Gg
    {
        $gg = Gg::getInstance();

        if (! count($parameters)) {
            return $gg;
        }

        return $gg->send($parameters);
    }
}

if (! function_exists('gd')) {
    /**
     * @throws ReflectionException
     * @throws ValueTypeException
     */
    function gd(...$parameters): Gg
    {
        gg($parameters);

        die();
    }
}
