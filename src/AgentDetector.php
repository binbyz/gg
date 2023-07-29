<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Traits\MakeTrait;

class AgentDetector
{
    use MakeTrait;

    public const VANILLA_PHP = 'Vanilla';
    public const LARAVEL_FRAMEWORK = 'Laravel';

    public static function detectFramework(): string
    {
        if (defined('LARAVEL_START')) {
            return self::LARAVEL_FRAMEWORK;
        }

        return self::VANILLA_PHP;
    }
}
