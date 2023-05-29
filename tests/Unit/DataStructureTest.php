<?php

use Beaverlabs\Gg\Dto\DataCapsuleDto;
use Beaverlabs\Gg\Dto\EnvironmentDto;
use Beaverlabs\Gg\Dto\MessageDto;
use Beaverlabs\Gg\MessageHandler;

test('환경변수 파일 테스트', function () {
    $data = EnvironmentDto::from([
        'host' => 'localhost',
        'port' => 21868,
    ]);

    expect($data)->toBeInstanceOf(EnvironmentDto::class)
        ->host->toBeString()
        ->port->toBeInt()
        ->getEndpoint()->toBe('http://localhost:21868');
});

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
        ->backtrace->toBeArray()
        ->and($result->data)->toBeInstanceOf(DataCapsuleDto::class)
            ->type->toBe('array')
            ->isScalarType->toBeFalse()
            ->namespace->toBeNull()
            ->className->toBeNull()
            ->value->toBeArray()
            ->value->toHaveCount(2) // 'test', 'test2'
                ->and($result->data->value)->toHaveKeys(['test', 'test2'])
                ->and($result->data->value['test'])->toBeInstanceOf(DataCapsuleDto::class)
                    ->type->toBe('integer')
                    ->isScalarType->toBeTrue()
                    ->namespace->toBeNull()
                    ->className->toBeNull()
                    ->value->toBe(1)
                ->and($result->data->value['test2'])->toBeInstanceOf(DataCapsuleDto::class);
});
