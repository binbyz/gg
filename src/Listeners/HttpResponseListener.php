<?php

namespace Beaverlabs\Gg\Listeners;

use Beaverlabs\Gg\Enums\MessageType;
use Beaverlabs\Gg\MessageHandler;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;

class HttpResponseListener
{
    public function handle(ResponseReceived $responseReceived): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        $request = $this->getRequestArray($responseReceived->request);
        $response = $this->getResponseArray($responseReceived->response);

        $messageData = MessageHandler::convert(['request' => $request, 'response' => $response], MessageType::HTTP_REQUEST);

        \gg()->send($messageData);
    }

    private function isEnabled(): bool
    {
        return \config('gg.listeners.http_response_listener', false);
    }

    private function getRequestArray(Request $request): array
    {
        return [
            'method' => $request->method(),
            'url' => $request->url(),
            'body' => $request->body(),
            'headers' => $this->flattenHeaders($request->headers()),
        ];
    }

    private function getResponseArray(Response $response): array
    {
        $headers = $response->headers();

        if (\array_key_exists('Content-Type', $headers) && \strpos($headers['Content-Type'][0], 'application/json') !== false) {
            $body = \json_decode($response->body(), true);
        } else {
            $charset = $this->extractResponseEncoding($response);
            $body = \mb_substr($response->body(), 0, 150, $charset) . '...';
        }

        $handlerStats = $response->handlerStats();

        return [
            'status' => $response->status(),
            'body' => $body,
            'headers' => $this->flattenHeaders($response->headers()),
            'cookies' => $response->cookies(),
            'handlerStats' => [
                'total_time' => $handlerStats['total_time'] ?? null,
                'primary_ip' => $handlerStats['primary_ip'] ?? null,
                'primary_port' => $handlerStats['primary_port'] ?? null,
            ],
        ];
    }

    private function extractResponseEncoding(Response $response): string
    {
        $contentType = $response->header('Content-Type');

        $parts = \array_map('trim', \array_filter(\explode(';', $contentType)));

        foreach ($parts as $part) {
            if (\strpos($part, 'charset=') !== false) {
                return \str_replace('charset=', '', $part);
            }
        }

        return 'UTF-8';
    }

    private function flattenHeaders(array $headers): array
    {
        return \array_map(function ($value) {
            return \is_array($value) ? \implode(' ', $value) : $value;
        }, $headers);
    }
}
