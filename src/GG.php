<?php

namespace Beaverlabs\GG;

use Beaverlabs\GG\Data\EnvironmentData;
use Beaverlabs\GG\Data\MessageData;

class GG
{
    /**
     * response status when sent data received successfully
     */
    const RESPONSE_STATUS = 'gg';

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
            $this->sendData(MessageHandler::convert($parameter));
        }
    }

    public function sendData(MessageData $message): bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:21868/api/receiver');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Beaverlabs/GG');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result == self::RESPONSE_STATUS;
    }
}
