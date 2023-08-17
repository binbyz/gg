<?php

use Beaverlabs\Gg\Models\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Artisan::call('migrate --path=../../../../database/migrations/2023_08_17_154952_test.php');

    Test::factory()->count(100)->create();
});

test('Query Listener Test', function () {
    $result = Test::where('name', 'apple')->limit(100)->get();

    expect($result)->toBeInstanceOf(Collection::class);
});
