<?php

namespace Beaverlabs\GG;

use Beaverlabs\GG\Data\EnvironmentData;

class GG
{
    /** @var GG */
    private static $instance;

    /** @var EnvironmentData */
    public $environments;

    private function __construct()
    {
        $this->environments = $this->loadEnvironments();
    }

    public function loadEnvironments(): EnvironmentData
    {
        return EnvironmentData::from([
            'host' => 'localhost',
            'port' => 21868,
        ]);
    }

    public static function getInstance(): GG
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function send(...$parameters): void
    {
        foreach ($parameters as $parameter) {
            $this->sendData(MessageConverter::convert($parameter));
        }
    }

    public function sendData(MessageConverter $message): void
    {
        // ...
    }
}
