<?php

use Beaverlabs\Gg\Dto\DataCapsuleDto;
use Beaverlabs\Gg\Dto\MessageDto;
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

    expect($result)->toBeInstanceOf(MessageDto::class)
        ->language->toBe('PHP')
        ->trace->toBeArray()
        ->and($result->data)->toBeInstanceOf(DataCapsuleDto::class)
            ->type->toBe('array')
            ->isScalar->toBeFalse()
            ->namespace->toBeNull()
            ->class->toBeNull()
            ->value->toBeArray()
            ->value->toHaveCount(2) // 'test', 'test2'
                ->and($result->data->value)->toHaveKeys(['test', 'test2'])
                ->and($result->data->value['test'])->toBeInstanceOf(DataCapsuleDto::class)
                    ->type->toBe('integer')
                    ->isScalar->toBeTrue()
                    ->namespace->toBeNull()
                    ->class->toBeNull()
                    ->value->toBe(1)
                ->and($result->data->value['test2'])->toBeInstanceOf(DataCapsuleDto::class);
});
