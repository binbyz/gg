<?php

use Beaverlabs\GG\Data\EnvironmentData;

it('make EnvironmentData test', function () {
    $data = EnvironmentData::from([
        'host' => 'localhost',
        'port' => 21868,
    ]);

    expect($data)->toBeInstanceOf(EnvironmentData::class)
        ->host->toBeString()
        ->port->toBeInt()
        ->getEndpoint()->toBe('http://localhost:21868');
});
