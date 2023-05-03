<?php

use Beaverlabs\GG\MessageHandler;

test('Data send test', function () {
    $result = gg()->sendData(MessageHandler::convert('test'));

    expect($result)->toBeTrue();
});
