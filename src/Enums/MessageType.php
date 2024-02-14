<?php

namespace Beaverlabs\Gg\Enums;

enum MessageType: string
{
    case LOG = 'log';
    case LOG_NOTE = 'log.note';
    case LOG_USAGE = 'log.usage';
    case THROWABLE = 'throwable';
    case HTTP_REQUEST = 'http.request';
    case HTTP_ROUTE_MATCHED = 'http.route.matched';
    case SQL_MODEL = 'sql.model';
}
