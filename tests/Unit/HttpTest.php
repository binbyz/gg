<?php

use Beaverlabs\Gg\ConfigVariables;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    config()->set(ConfigVariables::LISTENERS_HTTP_RESPONSE_LISTENER, true);
});

test('Http 전송 및 응답 미들웨어 Hook Test', function () {
    $response = Http::send('GET', 'https://phpgg.kr/api/v1/meta/version');
    $response = Http::send('POST', 'https://phpgg.kr');
    $response = Http::send('PATCH', 'https://phpgg.kr');
    $response = Http::send('DELETE', 'https://phpgg.kr');
    $response = Http::send('PUT', 'https://phpgg.kr');
    $response = Http::send('HEAD', 'https://phpgg.kr');
    $response = Http::send('OPTIONS', 'https://phpgg.kr');
    $response = Http::send('CONNECT', 'https://phpgg.kr');
    $response = Http::send('TRACE', 'https://phpgg.kr');

    expect($response->status())->toBe(Response::HTTP_OK);
});
