<?php

namespace Beaverlabs\Gg\Contracts;

interface MessageTypeEnum
{
    public const LOG = 'log';
    public const ERROR = 'throwable';
    public const SPACE = 'space';
}
