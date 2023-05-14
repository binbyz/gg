<?php

use Beaverlabs\GG\Exceptions\ValueTypeException;
use Beaverlabs\GG\GG;
use Beaverlabs\GG\MessageHandler;

test('스칼라 타입 데이터 전송', function () {
    $result = gg()->sendData(MessageHandler::convert(false));

    expect($result)->toBeTrue();
});

test('헬퍼 함수를 통한 데이터 전송', function () {
    expect(gg('123', 123, true, false, null, ['key1' => 'value1', 'key2' => 'value2']))
        ->toBeInstanceOf(GG::class);
});

test('Anonymous 클래스 전송 테스트', function () {
    $param2 = new class extends Beaverlabs\GG\Data {
        public $id = 1;
        public $name = 'WONBEEN IM';
        private $email = 'eyedroot@gmail.com';
        protected $password = '1234';
        public $array = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
    };

    expect(gg($param2))
        ->toBeInstanceOf(GG::class);
});

test('배열 전송 테스트', function () {
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

    expect(gg($param1))
        ->toBeInstanceOf(GG::class);
});

test('예외 클래스 전송 테스트', function () {
    $throw = ValueTypeException::make('Exception message send test');

    gg($throw);
    ray($throw);

    throw $throw;
})->throws(ValueTypeException::class);
