<?php

use Beaverlabs\Gg\Data\LineCodeData;
use Beaverlabs\Gg\Data\MessageData;
use Beaverlabs\Gg\Enums\MessageType;
use Beaverlabs\Gg\Exceptions\ValueTypeException;
use Beaverlabs\Gg\Gg;
use Illuminate\Support\Collection;

test('스칼라 타입 데이터 전송', function () {
    $result = gg(true);

    collect([1, 2, 3, 4, 5])->gg();

    expect($result)->toBeInstanceOf(Gg::class);
});

test('헬퍼 함수를 통한 데이터 전송', function () {
    expect(gg('123', 123, true, false, null, ['key1' => 'value1', 'key2' => 'value2']))
        ->toBeInstanceOf(Gg::class);
});

test('Anonymous 클래스 전송 테스트', function () {
    $parameter = new class extends \Beaverlabs\Gg\Data {
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
            'key3' => 14.45,
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
            'test2' => true,
        ],
    ];

    $param2 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    expect(gg($param1, $param2))
        ->toBeInstanceOf(Gg::class);
});

test('예외 클래스 전송 테스트', function () {
    $throw = ValueTypeException::make('Exception message send test');

    gg($throw);

    throw $throw;
})->throws(ValueTypeException::class);

test('space 전송 테스트', function () {
    $sequence = \gg(1)
        ->send(2)
        ->send(3);

    expect($sequence)
        ->toBeInstanceOf(Gg::class);
});

test('단일 전송 테스트', function () {
    $result = \gg(1, 2, 3);

    expect($result)
        ->toBeInstanceOf(Gg::class);
});

test('Throwable 전송 테스트', function () {
    $throw = new Exception('Exception message send test');

    expect(gg($throw))->toBeInstanceOf(Gg::class);
});

test('실수 및 정수 전송 테스트', function () {

    foreach (range(1, 100) as $value) {
        $result = gg($value);
    }

    expect($result)->toBeInstanceOf(Gg::class);
});

test('메모리 사용량 및 시간 측정 데이터 전송 테스트', function () {
    $result = gg()->begin();

    \gg([range(1, 10)])
        ->note('Hello, World, 안녕하세요.');

    expect($result)->toBeInstanceOf(Gg::class);

    \gg()->end();
});

test('링크 전송 테스트', function () {
    $result = \gg('asdf https://www.naver.com/ asdf');

    expect($result)->toBeInstanceOf(Gg::class);
});

test('데이터 압축 테스트', function () {
    $test = [
        Collection::range(1, 20),
        Collection::range(1, 20),
        Collection::range(1, 20),
        Collection::range(1, 20),
        Collection::range(1, 20),
        Collection::range(1, 20),
        Collection::range(1, 20),
        Collection::range(1, 20),
        Collection::range(1, 20),
    ];

    $result = \gg($test);

    expect($result)->toBeInstanceOf(Gg::class);
});

test('컬렉션 전송 테스트', function () {
    $result = \gg(Collection::range(1, 100));

    expect($result)->toBeInstanceOf(Gg::class);
});

test('샘플 데이터 전송', function () {
    $var1 = collect([
        LineCodeData::from(['line' => 1, 'code' => '<?php']),
        LineCodeData::from(['line' => 1, 'code' => '<?php']),
        LineCodeData::from(['line' => 1, 'code' => '<?php']),
        LineCodeData::from(['line' => 1, 'code' => '<?php']),
        LineCodeData::from(['line' => 1, 'code' => '<?php']),
    ]);


    $var2 = [
        'Hello, Word!',
        1,
        2,
        3,
        4,
        5,
    ];

    expect(gg($var1, $var2))->toBeInstanceOf(Gg::class);
});

test('sample#2 샘플 데이터 전송 2', function () {
    $data = [
        "items" => [
            [
                "name" => "Sword",
                "type" => "Weapon",
                "damage" => 50,
            ],
            [
                "name" => "Shield",
                "type" => "Armor",
                "defense" => 20,
            ],
        ],
        "characters" => [
            [
                "name" => "Knight",
                "level" => 10,
                "health" => 100,
                "stats" => [
                    "strength" => 15,
                    "agility" => 10,
                    "intelligence" => 5,
                ],
            ],
            [
                "name" => "Mage",
                "level" => 8,
                "health" => 80,
                "stats" => [
                    "strength" => 5,
                    "agility" => 10,
                    "intelligence" => 15,
                ],
            ],
            [
                "name" => "Archer",
                "level" => 9,
                "health" => 90,
                "stats" => [
                    "strength" => 10,
                    "agility" => 15,
                    "intelligence" => 10,
                ],
            ],
        ],
    ];

    expect(gg($data))->toBeInstanceOf(Gg::class);
});
