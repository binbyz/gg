<?php

namespace Beaverlabs\Gg;

class ConfigVariableChecker
{
    public static function is(ConfigVariables $config): bool
    {
        if (! in_array($config, ConfigVariables::cases(), true)) {
            return false;
        }

        return (bool) config($config->value, false);
    }
}
