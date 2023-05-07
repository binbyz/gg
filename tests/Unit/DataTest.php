<?php

use Beaverlabs\GG\Dto\EnvironmentDto;

it('make EnvironmentData test', function () {
    $data = EnvironmentDto::from([
        'host' => 'localhost',
        'port' => 21868,
    ]);

    expect($data)->toBeInstanceOf(EnvironmentDto::class)
        ->host->toBeString()
        ->port->toBeInt()
        ->getEndpoint()->toBe('http://localhost:21868');
});
