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
        $request = $this->getRequestArray($responseReceived->request);
        $response = $this->getResponseArray($responseReceived->response);

        $messageData = MessageHandler::convert(['request' => $request, 'response' => $response], MessageType::HTTP_REQUEST);

        \gg()->appendBuffer($messageData);
    }

    private function getRequestArray(Request $request): array
    {
        return [
            'method' => $request->method(),
            'url' => $request->url(),
            'body' => $request->body(),
            'headers' => $request->headers(),
        ];
    }

    private function getResponseArray(Response $response): array
    {
        return [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers(),
            'cookies' => $response->cookies(),
            'handlerStats' => $response->handlerStats(),
        ];
    }
}
