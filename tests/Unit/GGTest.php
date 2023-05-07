<?php

use Beaverlabs\GG\GG;
use Beaverlabs\GG\MessageHandler;

test('data send test', function () {
    $result = gg()->sendData(MessageHandler::convert(false));

    expect($result)->toBeTrue();
});

test('data send test via helper function', function () {
    expect(gg('123', 123, true, false, null, ['key1' => 'value1', 'key2' => 'value2']))
        ->toBeInstanceOf(GG::class);
});

test('object, array data send test', function () {
    $param1 = ['test' => 'test'];
    $param2 = new class extends Beaverlabs\GG\Data {
        public $id = 1;
        public $name = 'WONBEEN IM';
        private $email = 'eyedroot@gmail.com';
    };

    expect(gg($param1, $param2))
        ->toBeInstanceOf(GG::class);
});
