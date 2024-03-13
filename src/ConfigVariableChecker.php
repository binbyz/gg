<?php

namespace Beaverlabs\Gg;

class ConfigVariableChecker
{
    public static function is(ConfigVariables $config): bool
    {
        return (bool) config($config->value, false);
    }
}
