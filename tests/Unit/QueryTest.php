<?php

use Beaverlabs\Gg\ConfigVariables;
use Beaverlabs\Gg\Models\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    config()->set(ConfigVariables::LISTENERS_MODEL_QUERY_LISTENER->value, true);

    Artisan::call('migrate --path=../../../../database/migrations/2023_08_17_154952_test.php');
});

test('Query Listener Test', function () {
    $result = Test::where('name', "'\'''\"apple")
        ->whereIn('id', [1, 2, 3])
        ->where('id', '>', 1)
        ->where('id', '<', 10)
        ->orderBy('id', 'desc')
        ->limit(100)
        ->get();

    expect($result)->toBeInstanceOf(Collection::class);
});
