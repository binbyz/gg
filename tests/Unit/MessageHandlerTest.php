<?php

use Beaverlabs\GG\Data\MessageData;
use Beaverlabs\GG\MessageHandler;

test('MessageConverter Scalar Data Type', function () {
    expect(MessageHandler::convert([]))
        ->toBeInstanceOf(MessageData::class)
        ->toHaveProperties([
            'language' => 'PHP',
            'version' => phpversion(),
            'framework' => 'Vanilla',
            'isScalarType' => false,
            'data' => [],
        ]);
});

test('MessageData Class Test', function () {
    $converted = MessageHandler::convert('[]');

    expect($converted)
        ->toBeInstanceOf(MessageData::class)
        ->toHaveProperties([
            'language',
            'version',
            'framework',
            'isScalarType',
            'data',
            'backtrace'
        ]);
});

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
