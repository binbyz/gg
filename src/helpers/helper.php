<?php

use Beaverlabs\GG\GG;

if (! function_exists('gg')) {
    function gg(): GG
    {
        return GG::getInstance();
    }
}
