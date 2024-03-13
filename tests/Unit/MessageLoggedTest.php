<?php

use Illuminate\Support\Facades\Log;

test('MessageLogged 이벤트가 발동되면, 리스너가 작동해야 한다.', function () {
    Log::error('test');
});
