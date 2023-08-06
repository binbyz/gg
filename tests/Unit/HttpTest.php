<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

test('Http 전송 및 응답 미들웨어 Hook Test', function () {
    $response = Http::send('GET', 'https://www.google.com');

    expect($response->status())->toBe(Response::HTTP_OK);
});
