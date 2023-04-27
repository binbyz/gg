<?php

use Beaverlabs\GG\GG;

it('returns GG instance', function () {
    expect(\gg())->toBeInstanceOf(GG::class);
});

it('always return same memory address', function () {
    $hash = spl_object_hash(\gg());

    expect($hash)->toBe(spl_object_hash(\gg()))
        ->and($hash)->toBe(spl_object_hash(\gg()));
});
