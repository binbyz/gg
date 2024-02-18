<?php

use Beaverlabs\Gg\Data\DataCapsuleData;
use Beaverlabs\Gg\Data\MessageData;
use Beaverlabs\Gg\MessageHandler;

test('Array 캡슐라이징 테스트', function () {
    $data = [
        'test' => 1,
        'test2' => [
            'test3' => 2,
            'test4' => [
                'test5' => 3,
            ],
        ],
    ];

    $result = MessageHandler::convert($data);

    expect($result)->toBeInstanceOf(MessageData::class)
        ->language->toBe('PHP')
        ->trace->toBeArray()
        ->and($result->data)->toBeInstanceOf(DataCapsuleData::class)
        ->type->toBe('array')
        ->isScalar->toBeFalse()
        ->namespace->toBeNull()
        ->class->toBeNull()
        ->value->toBeArray()
        ->value->toHaveCount(2) // 'test', 'test2'
        ->and($result->data->value)->toHaveKeys(['test', 'test2'])
        ->and($result->data->value['test'])->toBeInstanceOf(DataCapsuleData::class)
        ->type->toBe('integer')
        ->isScalar->toBeTrue()
        ->namespace->toBeNull()
        ->class->toBeNull()
        ->value->toBe(1)
        ->and($result->data->value['test2'])->toBeInstanceOf(DataCapsuleData::class);
});
