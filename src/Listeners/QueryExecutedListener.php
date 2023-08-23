<?php

namespace Beaverlabs\Gg\Listeners;

use Beaverlabs\Gg\ConfigVariableChecker;
use Beaverlabs\Gg\ConfigVariables;
use Beaverlabs\Gg\Enums\MessageType;
use Beaverlabs\Gg\MessageHandler;
use Illuminate\Database\Events\QueryExecuted;

class QueryExecutedListener
{
    public function handle(QueryExecuted $event): void
    {
        if (! ConfigVariableChecker::is(ConfigVariables::LISTENERS_MODEL_QUERY_LISTENER)) {
            return;
        }

        $data = [
            'connectionName' => $event->connectionName,
            'time' => $event->time,
            'sql' => $this->escapeDoubleQuotes($event->sql),
            'bindings' => $event->bindings,
            'configs' => $event->connection->getConfig(),
        ];

        \gg()->send(
            MessageHandler::convert($data, MessageType::SQL_MODEL),
        );
    }

    private function escapeDoubleQuotes(string $sql): string
    {
        return \str_replace('"', '`', $sql);
    }
}
