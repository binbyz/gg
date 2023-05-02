<?php

use Beaverlabs\GG\Data\MessageData;
use Beaverlabs\GG\MessageHandler;

it('test MessageConverter of scalar data type', function () {
    expect(MessageHandler::convert([]))
        ->toBeInstanceOf(MessageData::class)
        ->toHaveProperties([
            'language' => 'PHP/'.phpversion(),
            'framework' => 'Vanilla',
            'isScalaType' => false,
            'data' => [],
        ]);
});

it('test MessageConverter to Json', function () {
    $converted = json_encode(MessageHandler::convert('[]'));

    $toBe = <<<TOBE
{"language":"PHP\/8.2.5","isScalaType":true,"framework":"Vanilla","data":"[]"}
TOBE;

    expect($converted)->toBe($toBe);
});
