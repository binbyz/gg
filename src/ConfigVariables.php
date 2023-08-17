<?php

namespace Beaverlabs\Gg;

interface ConfigVariables
{
    public const ENABLED = 'enabled';

    public const LISTENERS_HTTP_RESPONSE_LISTENER = 'listeners.http_response_listener';
    public const LISTENERS_MODEL_QUERY_LISTENER = 'listeners.model_query_listener';

    public const HOST = 'host';
    public const PORT = 'port';
}
