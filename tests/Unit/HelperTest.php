<?php

use Beaverlabs\Gg\Gg;

it('returns GG instance', function () {
    expect(\gg())->toBeInstanceOf(Gg::class);
});

it('always return same memory address', function () {
    $hash = spl_object_hash(\gg());

    expect($hash)->toBe(spl_object_hash(\gg()))
        ->and($hash)->toBe(spl_object_hash(\gg()));
});
