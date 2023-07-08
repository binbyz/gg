<?php

use Beaverlabs\Gg\Data;
use Beaverlabs\Gg\Dto\DataCapsuleDto;
use Beaverlabs\Gg\Dto\MessageDto;
use Beaverlabs\Gg\FrameworkDetector;
use Beaverlabs\Gg\MessageHandler;

test('MessageConverter Scalar Data Type', function () {
    expect(MessageHandler::convert([]))
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
            'type',
            'language',
            'version',
            'framework',
            'data',
            'trace',
        ])
        ->data->toBeInstanceOf(DataCapsuleDto::class)
        ->toHaveProperties([
            'type' => 'string',
            'isScalar' => true,
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
        ->toBeInstanceOf(MessageDto::class)
        ->data->toBeInstanceOf(DataCapsuleDto::class);
});

// todo fix this text code only laravel 5 times :(
test('Detect Framework Test', function (string $result, string $defineKey) {
    define($defineKey, true);

    expect(FrameworkDetector::detectFramework())->toBeString($result);
})->with([
    ['Laravel', 'LARAVEL_START'],
    ['WordPress', 'WPINC'],
    ['Yii', 'YII_BEGIN_TIME'],
    ['CodeIgniter', 'BASEPATH'],
    ['Vanilla', '__TEST__'],
]);

test('Normalize Class Name Test', function () {
    $class = new class extends Data {};

    expect(MessageHandler::normalizeclass($class))
        ->toBeString('Beaverlabs\Gg\Data@anonymous');
});
