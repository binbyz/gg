<?php

namespace Beaverlabs\Gg\Types;

interface MessageTypeEnum
{
    public const LOG = 'log';
    public const LOG_NOTE = 'log.note';
    public const LOG_USAGE = 'log.usage';
    public const THROWABLE = 'throwable';
}
