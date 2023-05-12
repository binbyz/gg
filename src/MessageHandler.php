<?php

namespace Beaverlabs\GG;

use Beaverlabs\GG\Dto\DataCapsuleDto;
use Beaverlabs\GG\Dto\MessageDto;
use Beaverlabs\GG\Exceptions\ValueTypeException;

class MessageHandler
{
    const ANONYMOUS_CLASS_PREFIX = '@anonymous';

    /** @var mixed */
    private $data;

    private function __construct($data)
    {
        $this->data = $data;
    }

    public static function convert($data): MessageDto
    {
        $self = new self($data);

        return MessageDto::from([
            'language' => 'PHP',
            'version' => \phpversion(),
            'framework' => static::detectFramework(),
            'data' => $self->capsulizeRecursively($self->data),
            'backtrace' => debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 2000),
        ]);
    }

    public static function isScalarType($data): bool
    {
        return is_scalar($data) || is_null($data);
    }

    /**
     * @throws ValueTypeException
     */
    public function capsulizeRecursively($data): DataCapsuleDto
    {
        if (static::isScalarType($data)) {
            return $this->capsuleScalarType($data);
        }

        if (\is_array($data)) {
            return $this->capsuleArrayType($data);
        }

        return $this->capsuleObjectType($data);
    }

    private function capsuleScalarType($data): DataCapsuleDto
    {
        return DataCapsuleDto::from([
            'type' => gettype($data),
            'isScalarType' => true,
            'value' => $data,
        ]);
    }

    /**
     * @throws ValueTypeException
     */
    private function capsuleArrayType($data): DataCapsuleDto
    {
        if (! \is_array($data)) {
            throw ValueTypeException::make($data);
        }

        return DataCapsuleDto::from([
            'type' => gettype($data),
            'isScalarType' => false,
            'namespace' => null,
            'className' => null,
            'value' => \array_map(function ($item) {
                return $this->capsulizeRecursively($item);
            }, $data),
        ]);
    }

    /**
     * @throws ValueTypeException
     */
    private function capsuleObjectType($data): DataCapsuleDto
    {
        if (! \is_object($data)) {
            throw ValueTypeException::make($data);
        }

        return DataCapsuleDto::from([
            'type' => gettype($data),
            'isScalarType' => false,
            'namespace' => static::getNamespace($data),
            'className' => static::normalizeClassName($data),
            'value' => \array_map(function ($item) {
                return $this->capsulizeRecursively($item);
            }, \get_object_vars($data)),
        ]);
    }

    public static function getNamespace($data): string
    {
        $namespace = \explode('\\', \get_class($data));
        \array_pop($namespace);

        return \implode('\\', $namespace);
    }

    public static function normalizeClassName($data): string
    {
        $className = \get_class($data);

        if (\strpos($className, self::ANONYMOUS_CLASS_PREFIX) !== false) {
            $exploded = explode(self::ANONYMOUS_CLASS_PREFIX, $className);

            $className = $exploded[0] . self::ANONYMOUS_CLASS_PREFIX;
        }

        return $className;
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
}
