<?php

use Beaverlabs\GG\MessageHandler;

it('test MessageConverter of scalar data type', function () {
    expect(MessageHandler::convert('test')
        ->isScalarType())->toBeTrue()
        ->and(MessageHandler::convert(1)
            ->isScalarType())->toBeTrue()
        ->and(MessageHandler::convert(1.1)
            ->isScalarType())->toBeTrue()
        ->and(MessageHandler::convert(true)
            ->isScalarType())->toBeTrue()
        ->and(MessageHandler::convert(null)
            ->isScalarType())->toBeTrue()
        ->and(MessageHandler::convert(['test'])
            ->isScalarType())->toBeFalse();
});
