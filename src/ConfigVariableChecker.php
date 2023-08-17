<?php

namespace Beaverlabs\Gg;

class ConfigVariableChecker implements ConfigVariables
{
    public const ROOT_KEY = 'gg';

    protected static array $variables = [
        ConfigVariables::ENABLED,
        ConfigVariables::LISTENERS_HTTP_RESPONSE_LISTENER,
        ConfigVariables::LISTENERS_MODEL_QUERY_LISTENER,
        ConfigVariables::HOST,
        ConfigVariables::PORT,
    ];

    public static function is(string $key): bool
    {
        if (! \in_array($key, self::$variables, true)) {
            return false;
        }

        $key = (self::ROOT_KEY . '.' . \strtolower($key));

        return (bool) \config($key, false);
    }
}
