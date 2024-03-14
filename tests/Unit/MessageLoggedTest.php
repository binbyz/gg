<?php

use Illuminate\Log\Events\MessageLogged;

beforeEach(function () {
    config(['gg.listeners.exception_listener' => true]);
});

test('MessageLogged 이벤트가 발동되면, 리스너가 작동해야 한다.', function () {
    try {
        throw new InvalidArgumentException('test');
    } catch (InvalidArgumentException $e) {
        event(new MessageLogged('error', 'test', ['exception' => $e]));

        throw $e;
    }
})->throws(InvalidArgumentException::class);
