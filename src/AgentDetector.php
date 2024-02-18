<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Traits\MakeTrait;

class AgentDetector
{
    use MakeTrait;

    public const string VANILLA_PHP = 'Vanilla';

    public const string LARAVEL_FRAMEWORK = 'Laravel';

    public static function detectFramework(): string
    {
        if (defined('LARAVEL_START')) {
            return self::LARAVEL_FRAMEWORK;
        }

        return self::VANILLA_PHP;
    }
}
