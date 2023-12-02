<?php

return [
    'enabled' => env('GG_ENABLED', true),
    'listeners' => [
        'exception_listener' => env('GG_EXCEPTION_LISTENER', false),
        'http_response_listener' => env('GG_HTTP_RESPONSE_LISTENER', false),
        'model_query_listener' => env('GG_MODEL_QUERY_LISTENER', false),
    ],
    'host' => env('GG_HOST', 'host.docker.internal'),
    'port' => env('GG_PORT', 21868),
];
