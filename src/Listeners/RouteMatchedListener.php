<?php

namespace Beaverlabs\Gg\Listeners;

use Beaverlabs\Gg\ConfigVariableChecker;
use Beaverlabs\Gg\ConfigVariables;
use Beaverlabs\Gg\Enums\MessageType;
use Beaverlabs\Gg\MessageHandler;
use Illuminate\Routing\Events\RouteMatched;

class RouteMatchedListener
{
    public function handle(RouteMatched $event)
    {
        if (! ConfigVariableChecker::is(ConfigVariables::LISTENERS_HTTP_ROUTE_MATCHED_LISTENER)) {
            return;
        }

        $data = [
            'method' => $event->request->getMethod(),
            'url' => $event->request->getUri(),
            'controller' => $event->route->getAction('controller'),
            'routeName' => $event->route->getAction('as'),
            'middleware' => $event->route->getAction('middleware'),
            'exclude_middlewares' => $event->route->getAction('excluded_middleware'),
        ];

        \gg()->send(
            MessageHandler::convert($data, MessageType::HTTP_ROUTE_MATCHED),
        );
    }
}
