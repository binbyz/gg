<?php

use Beaverlabs\GG\GG;
use Beaverlabs\GG\MessageHandler;

test('data send test', function () {
    $result = gg()->sendData(MessageHandler::convert(false));

    expect($result)->toBeTrue();
});

test('data send test via helper function', function () {
    expect(gg('123', 123, true, false, null, ['test' => 'test']))
        ->toBeInstanceOf(GG::class);
});
