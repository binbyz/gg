<?php

use Beaverlabs\Gg\ConfigVariableChecker;
use Beaverlabs\Gg\ConfigVariables;

test('gg.enabled false', function () {
    \config()->set('gg.enabled', false);

    expect(ConfigVariableChecker::is(ConfigVariables::ENABLED))
        ->toBeFalse();
});

test('gg.enabled true', function () {
    \config()->set('gg.enabled', true);

    expect(ConfigVariableChecker::is(ConfigVariables::ENABLED))
        ->toBeTrue();
});
