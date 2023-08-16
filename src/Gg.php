<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Enums\MessageType;

class Gg
{
    const BUFFER_CHUNK_SIZE = 5;

    private bool $enabled = true;

    private static string $userAgent = 'Beaverlabs/GG';

    private float $beginTime = 0;
    private float $beginMemory = 0;

    private bool $flagBacktrace = false;

    private array $buffer = [];

    public GgConnection $connection;

    public function __construct()
    {
        $this->connection = GgConnection::make();

        $local = \trim(\strtolower(\getenv('GG_ENABLED')));

        $this->enabled = ! (\strtolower($local) === 'false');
    }

    public function __destruct()
    {
        if ($this->enabled) {
            $this->sendData();
        }

        $this->clear();
    }

    private function clear()
    {
        unset($this->buffer);
        $this->buffer = [];
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
        $stringValue = \is_callable($conditionOrStringData) ? $value : $conditionOrStringData;

        if (\is_callable($conditionOrStringData) && ! $conditionOrStringData()) {
            return $this;
        }

        $this->appendBuffer(
            MessageHandler::convert((string) $stringValue, MessageType::LOG_NOTE, false),
        );

        return $this;
    }

    public function die()
    {
        die();
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
            $memoryUsage = round($memoryUsage / 1024 / 1024, 2) . ' MB';
        } else {
            $memoryUsage = round($memoryUsage / 1024, 2) . ' KB';
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
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 1000);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        while (! empty($this->buffer)) {
            $chunk = \array_splice($this->buffer, 0, self::BUFFER_CHUNK_SIZE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($chunk));
            curl_exec($ch);
        }

        curl_close($ch);
    }

    public function getEndpoint(): string
    {
        return sprintf('http://%s:%d/api/receiver', $this->connection->host, $this->connection->port);
    }
}
