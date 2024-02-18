<?php

namespace Beaverlabs\Gg;

interface ConfigVariables
{
    public const string ENABLED = 'gg.enabled';

    public const string LISTENERS_EXCEPTION_LISTENER = 'gg.listeners.exception_handler_listener';

    public const string LISTENERS_HTTP_RESPONSE_LISTENER = 'gg.listeners.http_response_listener';

    public const string LISTENERS_HTTP_ROUTE_MATCHED_LISTENER = 'gg.listeners.http_route_matched_listener';

    public const string LISTENERS_MODEL_QUERY_LISTENER = 'gg.listeners.model_query_listener';

    public const string HOST = 'gg.host';

    public const string PORT = 'gg.port';
}
