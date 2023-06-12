<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Contracts\MessageTypeEnum;
use Beaverlabs\Gg\Dto\MessageDto;
use Beaverlabs\Gg\Exceptions\ValueTypeException;
use ReflectionException;

class Gg
{
    /**
     * response status when sent data received successfully
     */
    const RESPONSE_STATUS = 'gg';

    private static ?Gg $instance = null;

    public static string $userAgent = 'Beaverlabs/GG';

    public GgConnection $connection;

    private function __construct()
    {
        $this->connection = GgConnection::make();
    }

    public function bindConnection(GgConnection $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    public static function getInstance(): Gg
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
        if (! \is_array($parameters)) {
            $parameters = [$parameters];
        }

        if (! count($parameters)) {
            return static::getInstance();
        }

        foreach ($parameters as $parameter) {
            $this->sendData(MessageHandler::convert($parameter));
        }

        return static::getInstance();
    }

    /**
     * @throws ReflectionException
     * @throws ValueTypeException
     */
    public function space($conditionOrStringData = null): self
    {
        if (\is_callable($conditionOrStringData) && ! $conditionOrStringData()) {
            return static::getInstance();
        }

        $this->sendData(
            MessageHandler::convert((string) $conditionOrStringData, MessageTypeEnum::SPACE, false),
        );

        return static::getInstance();
    }

    public function die()
    {
        die();
    }

    protected function sendData(MessageDto $message): bool
    {
        $endpoint = $this->getEndpoint();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 500);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result == self::RESPONSE_STATUS;
    }

    public function getEndpoint(): string
    {
        return sprintf('http://%s:%d/api/receiver', $this->connection->host, $this->connection->port);
    }
}
