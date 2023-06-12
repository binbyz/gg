<?php

use Beaverlabs\Gg\Exceptions\ValueTypeException;
use Beaverlabs\Gg\Gg;
use Beaverlabs\Gg\MessageHandler;

test('스칼라 타입 데이터 전송', function () {
    $result = gg(true);

    expect($result)->toBeInstanceOf(Gg::class);
});

test('헬퍼 함수를 통한 데이터 전송', function () {
    expect(gg('123', 123, true, false, null, ['key1' => 'value1', 'key2' => 'value2']))
        ->toBeInstanceOf(Gg::class);
});

test('Anonymous 클래스 전송 테스트', function () {
    $parameter = new class extends Beaverlabs\Gg\Data {
        public $id = 1;
        public $name = 'WONBEEN IM';
        private $email = 'eyedroot@gmail.com';
        protected $password = '1234';
        public $array = [
            'key1' => 'value1',
            'key2' => 'value2',
            'depth3' => [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value2',
                'key4' => 'value2',
            ],
        ];

        public function __construct()
        {
            $this->array['depth3']['key5'] = new class {
                public $id = 1;
                public $name = 'WONBEEN IM';
                private $email = 'byzz@kakao.com';
                protected $password = '1234';
                public $address = 'South Korea';
                public $phoneNumber = 12345667;
            };
        }
    };

    expect(gg($parameter))
        ->toBeInstanceOf(Gg::class);
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
        ->toBeInstanceOf(Gg::class);
});

test('예외 클래스 전송 테스트', function () {
    $throw = ValueTypeException::make('Exception message send test');

    gg($throw);

    throw $throw;
})->throws(ValueTypeException::class);

test('space 전송 테스트', function () {
    $sequence = \gg(1)
        ->space(fn () => false)
        ->send(2)
        ->space('is divider')
        ->send(3);

    expect($sequence)
        ->toBeInstanceOf(Gg::class);
});

test('단일 전송 테스트', function () {
    $result = \gg(1, 2, 3);

    expect($result)
        ->toBeInstanceOf(Gg::class);
});
