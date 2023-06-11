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
        foreach ($parameters as $parameter) {
            Gg::getInstance()->send($parameter);
        }

        return Gg::getInstance();
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
