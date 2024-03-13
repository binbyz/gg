<?php

namespace Beaverlabs\Gg\Listeners;

use Beaverlabs\Gg\ConfigVariableChecker;
use Beaverlabs\Gg\ConfigVariables;
use Beaverlabs\Gg\Data\DataCapsuleData;
use Beaverlabs\Gg\Data\ThrowableData;
use Beaverlabs\Gg\Enums\MessageType;
use Beaverlabs\Gg\MessageHandler;
use Illuminate\Log\Events\MessageLogged;

class ExceptionListener
{
    public function handle(MessageLogged $logged): void
    {
        if (! ConfigVariableChecker::is(ConfigVariables::LISTENERS_EXCEPTION_LISTENER)) {
            return;
        }

        if (array_key_exists('exception', $logged->context) && $logged->context['exception'] instanceof \Throwable) {
            gtrace($logged->context['exception']);
        } else {
            $exceptionData = [
                'type' => gettype($logged->message),
                'isScalar' => true,
                'namespace' => '',
                'class' => '',
                'value' => ThrowableData::from([
                    'message' => $logged->message,
                    'code' => 0,
                    'file' => '',
                    'line' => 0,
                    'previous' => null,
                ]),
            ];

            gg()->send(
                MessageHandler::convert(DataCapsuleData::from($exceptionData), MessageType::THROWABLE),
            );
        }
    }
}
