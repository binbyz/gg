<?php

return [
    'enabled' => env('GG_ENABLED', true),
    'listeners' => [
        'http_response_listener' => env('GG_HTTP_RESPONSE_LISTENER', false),
    ],
    'host' => env('GG_HOST', 'host.docker.internal'),
    'port' => env('GG_PORT', 21868),
];
