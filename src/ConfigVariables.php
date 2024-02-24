<?php

namespace Beaverlabs\Gg;

enum ConfigVariables: string
{
    case ENABLED = 'gg.enabled';
    case LISTENERS_EXCEPTION_LISTENER = 'gg.listeners.exception_handler_listener';
    case LISTENERS_HTTP_RESPONSE_LISTENER = 'gg.listeners.http_response_listener';
    case LISTENERS_HTTP_ROUTE_MATCHED_LISTENER = 'gg.listeners.http_route_matched_listener';
    case LISTENERS_MODEL_QUERY_LISTENER = 'gg.listeners.model_query_listener';
    case HOST = 'gg.host';
    case PORT = 'gg.port';
}
