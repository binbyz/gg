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
            'isScalaType' => false,
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
            'isScalaType',
            'data',
            'backtrace'
        ]);
});
