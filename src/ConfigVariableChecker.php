<?php

namespace Beaverlabs\Gg;

class ConfigVariableChecker implements ConfigVariables
{
    protected static array $variables = [
        ConfigVariables::ENABLED,
        ConfigVariables::LISTENERS_EXCEPTION_LISTENER,
        ConfigVariables::LISTENERS_HTTP_RESPONSE_LISTENER,
        ConfigVariables::LISTENERS_HTTP_ROUTE_MATCHED_LISTENER,
        ConfigVariables::LISTENERS_MODEL_QUERY_LISTENER,
        ConfigVariables::HOST,
        ConfigVariables::PORT,
    ];

    public static function is(string $key): bool
    {
        if (! \in_array($key, self::$variables, true)) {
            return false;
        }

        return (bool) \config($key, false);
    }
}
