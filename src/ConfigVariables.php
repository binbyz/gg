<?php

namespace Beaverlabs\Gg;

interface ConfigVariables
{
    public const ENABLED = 'gg.enabled';

    public const LISTENERS_EXCEPTION_LISTENER = 'gg.listeners.exception_handler_listener';
    public const LISTENERS_HTTP_RESPONSE_LISTENER = 'gg.listeners.http_response_listener';
    public const LISTENERS_HTTP_ROUTE_MATCHED_LISTENER = 'gg.listeners.http_route_matched_listener';
    public const LISTENERS_MODEL_QUERY_LISTENER = 'gg.listeners.model_query_listener';

    public const HOST = 'gg.host';
    public const PORT = 'gg.port';
}
