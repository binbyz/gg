<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Data\MessageData;
use Beaverlabs\Gg\Enums\MessageType;
use MessagePack\MessagePack;

class Gg
{
    const int BUFFER_CHUNK_SIZE = 5;

    private bool $enabled;

    private float $beginTime = 0;

    private float $beginMemory = 0;

    private bool $flagBacktrace = false;

    private array $buffer = [];

    private static string $userAgent = 'Beaverlabs/GG';

    public GgConnection $connection;

    public function __construct()
    {
        $this->connection = GgConnection::make();
        $this->enabled = config('gg.enabled', true);
    }

    public function bindConnection(GgConnection $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    public function send(...$parameters): self
    {
        if (! $this->enabled) {
            return $this;
        }

        if (! count($parameters)) {
            return $this;
        }

        foreach ($parameters as $parameter) {
            if ($parameter instanceof MessageData) {
                $this->appendBuffer($parameter);

                continue;
            }

            $this->appendBuffer(
                MessageHandler::convert(
                    $parameter,
                    null,
                    $parameter instanceof \Throwable ? true : $this->flagBacktrace,
                ),
            );
        }

        $this->sendData();

        return $this;
    }

    public function onTrace(): self
    {
        $this->flagBacktrace = true;

        return $this;
    }

    public function note($conditionOrStringData = null, $value = null): self
    {
        $stringValue = is_callable($conditionOrStringData) ? $value : $conditionOrStringData;

        if (is_callable($conditionOrStringData) && ! $conditionOrStringData()) {
            return $this;
        }

        $this->appendBuffer(
            MessageHandler::convert((string) $stringValue, MessageType::LOG_NOTE, false),
        );

        return $this;
    }

    public function die(): void
    {
        exit();
    }

    public function begin(): self
    {
        $this->beginTime = microtime(true);
        $this->beginMemory = memory_get_usage();

        return $this;
    }

    public function end(): self
    {
        $endMemory = memory_get_usage();
        $memoryUsage = $endMemory - $this->beginMemory;

        $data = [
            'beginMemory' => $this->formatBytes($this->beginMemory),
            'endMemory' => $this->formatBytes($endMemory),
            'diffMemory' => $this->formatBytes($memoryUsage),
            'executeTime' => microtime(true) - $this->beginTime,
        ];

        $message = MessageHandler::convert($data, MessageType::LOG_USAGE, false);

        $this->appendBuffer($message);

        return $this;
    }

    private function formatBytes($memoryUsage): string
    {
        if ($memoryUsage > 1024 * 1024) {
            $memoryUsage = round($memoryUsage / 1024 / 1024, 2).' MB';
        } else {
            $memoryUsage = round($memoryUsage / 1024, 2).' KB';
        }

        return $memoryUsage;
    }

    protected function appendBuffer($data): self
    {
        if (! $this->enabled) {
            return $this;
        }

        $this->buffer[] = $data;

        return $this;
    }

    private function sendData(): void
    {
        $endpoint = $this->getEndpoint();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5_000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30_000);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-msgpack']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        while (! empty($this->buffer)) {
            $chunk = array_splice($this->buffer, 0, self::BUFFER_CHUNK_SIZE);

            $chunk = array_map(function (Data $data) {
                return $data->toArray();
            }, $chunk);

            curl_setopt($ch, CURLOPT_POSTFIELDS, MessagePack::pack($chunk));
            curl_exec($ch);
        }

        curl_close($ch);
    }

    public function getEndpoint(): string
    {
        return sprintf('http://%s:%d/api/receiver', $this->connection->host, $this->connection->port);
    }
}
