<?php

namespace Beaverlabs\Gg\Enums;

interface MessageType
{
    public const LOG = 'log';
    public const LOG_NOTE = 'log.note';
    public const LOG_USAGE = 'log.usage';
    public const THROWABLE = 'throwable';
    public const HTTP_REQUEST = 'http.request';
    public const SQL_MODEL = 'sql.model';
}
