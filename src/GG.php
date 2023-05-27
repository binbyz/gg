<?php

namespace Beaverlabs\GG;

use Beaverlabs\GG\Dto\EnvironmentDto;
use Beaverlabs\GG\Dto\MessageDto;
use Beaverlabs\GG\Exceptions\ValueTypeException;
use ReflectionException;

class GG
{
    /**
     * response status when sent data received successfully
     */
    const RESPONSE_STATUS = 'gg';

    private static GG $instance;

    public EnvironmentDto $environments;

    private function __construct()
    {
        $this->environments = $this->loadEnvironments();
    }

    public function loadEnvironments(): EnvironmentDto
    {
        return EnvironmentDto::from([
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

    /**
     * @throws ReflectionException
     * @throws ValueTypeException
     */
    public function send(...$parameters): self
    {
        if (! count($parameters)) {
            return static::getInstance();
        }

        foreach (\current($parameters) as $parameter) {
            $this->sendData(MessageHandler::convert($parameter));
        }

        return static::getInstance();
    }

    public function sendData(MessageDto $message): bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:21868/api/receiver');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 500);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);
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
