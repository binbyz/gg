<?php

namespace Beaverlabs\GG;

use Beaverlabs\GG\Data\MessageData;

class MessageHandler
{
    /** @var mixed */
    public $data;

    /** @var bool */
    public $isScalaType;

    public function __construct($data)
    {
        $this->data = $data;
        $this->isScalaType = $this->isScalarType();
    }

    public static function convert($data): MessageData
    {
        $self = new self($data);

        return MessageData::from([
            'language' => 'PHP',
            'version' => \phpversion(),
            'framework' => $self->detectFramework(),
            'isScalarType' => $self->isScalarType(),
            'type' => gettype($data), // 'array', 'string', 'integer', 'boolean', 'object', 'NULL'
            'data' => $data,
            'backtrace' => debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 2000),
        ]);
    }

    public static function detectFramework(): string
    {
        if (defined('LARAVEL_START')) {
            return 'Laravel';
        }

        if (class_exists('Symfony\Component\HttpKernel\Kernel')) {
            return 'Symfony';
        }

        if (defined('WPINC')) {
            return 'WordPress';
        }

        if (defined('YII_BEGIN_TIME')) {
            return 'Yii';
        }

        if (defined('BASEPATH')) {
            return 'CodeIgniter';
        }

        return 'Vanilla';
    }

    public function isScalarType(): bool
    {
        return is_scalar($this->data) || is_null($this->data);
    }
}
