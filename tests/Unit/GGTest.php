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
//    $param1 = ['test' => 'test'];
    $param2 = new class extends Beaverlabs\GG\Data {
        public $id = 1;
        public $name = 'WONBEEN IM';
        private $email = 'eyedroot@gmail.com';
        public $array = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
    };

    ray($param2);

    expect(gg($param2))
        ->toBeInstanceOf(GG::class);
});

test('array data send test', function () {
    $param1 = [
        'test' => 'test',
        'key' => 1,
        'key2' => 2,
        'key4' => 2,
        'key5' => 2,
        'key6' => 2,
        'key7' => 2,
        'key8' => [
            'test' => 1,
            'test2' => 2,
        ],
    ];

    ray($param1);

    expect(gg($param1))
        ->toBeInstanceOf(GG::class);
});
