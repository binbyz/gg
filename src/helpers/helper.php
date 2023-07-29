<?php

use Beaverlabs\Gg\Gg;

if (! function_exists('ggInstance')) {
    function ggInstance(): Gg
    {
        if (function_exists('app')) {
            return app(Gg::class);
        }

        return new Gg();
    }
}

if (! function_exists('gg')) {
    function gg(...$parameters): Gg
    {
        $instance = ggInstance();

        if (count($parameters)) {
            foreach ($parameters as $parameter) {
                $instance->send($parameter);
            }
        }

        return $instance;
    }
}

if (! function_exists('gtrace')) {
    function gtrace(...$parameters): Gg
    {
        return \gg()->onTrace()->send(...$parameters);
    }
}

if (! function_exists('gd')) {
    function gd(...$parameters): void
    {
        \gg(...$parameters)->die();
    }
}
