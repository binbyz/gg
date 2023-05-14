<?php

use Beaverlabs\GG\Data;
use Beaverlabs\GG\Dto\DataCapsuleDto;
use Beaverlabs\GG\Dto\MessageDto;
use Beaverlabs\GG\MessageHandler;

test('MessageConverter Scalar Data Type', function () {
    expect(MessageHandler::convert([]))
        ->ray()
        ->toBeInstanceOf(MessageDto::class)
        ->toHaveProperties([
            'language',
            'version',
            'framework',
            'data',
        ]);
});

test('String Message Data Test', function () {
    $converted = MessageHandler::convert('[]');

    expect($converted)
        ->toBeInstanceOf(MessageDto::class)
        ->toHaveProperties([
            'language',
            'version',
            'framework',
            'data',
            'backtrace'
        ])
        ->data->toBeInstanceOf(DataCapsuleDto::class)
        ->toHaveProperties([
            'type' => 'string',
            'isScalarType' => true,
            'value' => '[]',
        ]);
});

test('Array Message Data Test (1)', function () {
    $converted = MessageHandler::convert([
        'foo' => 'bar',
        'baz' => 'qux',
        'function' => function () {
            echo 1;
        },
    ]);

    expect($converted)
        ->ray()
        ->toBeInstanceOf(MessageDto::class)
        ->data->toBeInstanceOf(DataCapsuleDto::class);
});

test('Array Message Data Test (2)', function () {
    $converted = MessageHandler::convert([
        'foo' => 'bar',
        'baz' => 'qux',
        'quux' => [
            'corge' => 'grault',
            'garply' => 'waldo',
            'fred' => [1, 2, 3, 4, 5, 100],
        ],
    ]);

    expect($converted)
        ->ray()
        ->toBeInstanceOf(MessageDto::class)
        ->data->toBeInstanceOf(DataCapsuleDto::class);
});

test('Object Message Data Test', function () {
    $data = new class extends Data {
        public $foo = 'bar';
        public $baz = 'qux';
        private $quux = 'corge';
    };

    $converted = MessageHandler::convert($data);

    expect($converted)
        ->ray()
        ->toBeInstanceOf(MessageDto::class)
        ->data->toBeInstanceOf(DataCapsuleDto::class);
});

// todo fix this text code only laravel 5 times :(
test('Detect Framework Test', function (string $result, string $defineKey) {
    define($defineKey, true);

    expect(MessageHandler::detectFramework())->toBeString($result);
})->with([
    ['Laravel', 'LARAVEL_START'],
    ['WordPress', 'WPINC'],
    ['Yii', 'YII_BEGIN_TIME'],
    ['CodeIgniter', 'BASEPATH'],
    ['Vanilla', '__TEST__'],
]);

test('Normalize Class Name Test', function () {
    $class = new class extends Data {};

    expect(MessageHandler::normalizeClassName($class))
        ->toBeString('Beaverlabs\GG\Data@anonymous');
});
