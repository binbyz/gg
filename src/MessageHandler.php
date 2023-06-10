<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Dto\DataCapsuleDto;
use Beaverlabs\Gg\Dto\MessageDto;
use Beaverlabs\Gg\Dto\ThrowableDto;
use Beaverlabs\Gg\Exceptions\ValueTypeException;
use ReflectionException;

class MessageHandler
{
    const SANITIZE_HELPER_FUNCTION = [
    ];

    const SANITIZE_BACKTRACE_CLASSES = [
    ];

    const DEBUG_BACKTRACE_LIMIT = 50;
    const ANONYMOUS_CLASS_PREFIX = '@anonymous';
    const MODIFIER_SPLITTER = '@';

    /** @var mixed */
    private $data;

    private function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @throws ValueTypeException
     * @throws ReflectionException
     */
    public static function convert($data): MessageDto
    {
        $self = new self($data);

        $backtrace = \array_map(
            function ($row) {
                if (\array_key_exists('object', $row)) {
                    unset($row['object']);
                }

                return $row;
            },
            debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, self::DEBUG_BACKTRACE_LIMIT),
        );

        return MessageDto::from([
            'language' => 'PHP',
            'version' => \phpversion(),
            'framework' => FrameworkDetector::detectFramework(),
            'data' => $self->capsulizeRecursively($self->data),
            'backtrace' => $self->capsulizeBacktraceRecursively($self->sanitizeBacktrace($backtrace))
        ]);
    }

    public static function isScalarType($data): bool
    {
        return is_scalar($data) || is_null($data);
    }

    /**
     * @throws ValueTypeException
     * @throws ReflectionException
     */
    public function capsulizeRecursively($data): DataCapsuleDto
    {
        if ($data instanceof DataCapsuleDto) {
            return $data;
        }

        if (static::isScalarType($data)) {
            return $this->capsulizeScalar($data);
        }

        if (\is_array($data)) {
            return $this->capsulizeArray($data);
        }

        if ($data instanceof \Throwable) {
            return $this->capsulizeThrowable($data);
        }

        return $this->capsulizeObject($data);
    }

    public function capsulizeBacktraceRecursively(array $backtrace): array
    {
        return \array_map(
            /**
            * @throws ReflectionException
            * @throws ValueTypeException
            */
            function ($item) {
                $item['args'] = $this->capsulizeRecursively($item['args']);

                return $item;
            },
            $backtrace
        );
    }

    public function sanitizeBacktrace(array $backtrace): array
    {
        $backtrace = \array_filter($backtrace, static function (array $item) {
            if (\array_key_exists('class', $item)) {
                foreach (self::SANITIZE_BACKTRACE_CLASSES as $className) {
                    if (\strpos($item['class'], $className) > -1) {
                        return false;
                    }
                }
            }

            if (\array_key_exists('function', $item)) {
                foreach (self::SANITIZE_HELPER_FUNCTION as $helperFunction) {
                    if (\strpos($item['function'], $helperFunction) > -1) {
                        return false;
                    }
                }
            }

            return true;
        });

        $backtrace = \array_map(static function (array $item) {
            if (\array_key_exists('args', $item) && \is_array($item['args'])) {
                $item['args'] = \array_map(static function ($arg) {
                    if (\is_object($arg)) {
                        return DataCapsuleDto::from([
                            'type' => 'class',
                            'isScalarType' => false,
                            'namespace' => static::getNamespace($arg),
                            'className' => static::normalizeClassName($arg),
                            'pruned' => true,
                            'value' => [],
                        ]);
                    }

                    return $arg;
                }, $item['args']);
            }

            return $item;
        }, $backtrace);

        return \array_values($backtrace);
    }

    private function capsulizeScalar($data): DataCapsuleDto
    {
        return DataCapsuleDto::from([
            'type' => gettype($data),
            'isScalarType' => true,
            'value' => $data,
        ]);
    }

    /**
     * @throws ValueTypeException
     * @throws ReflectionException
     */
    public function capsulizeArray($data): DataCapsuleDto
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
     * @throws ReflectionException
     */
    public function capsulizeObject($data): DataCapsuleDto
    {
        if (! \is_object($data)) {
            throw ValueTypeException::make($data);
        }

        return DataCapsuleDto::from([
            'type' => gettype($data),
            'isScalarType' => false,
            'namespace' => static::getNamespace($data),
            'className' => static::normalizeClassName($data),
            'value' => $this->getPropertiesToArray($data),
        ]);
    }

    /**
     * @throws ValueTypeException
     */
    public function capsulizeThrowable($data): DataCapsuleDto
    {
        if (! $data instanceof \Throwable) {
            throw ValueTypeException::make($data);
        }

        return DataCapsuleDto::from([
            'type' => 'throwable',
            'isScalarType' => false,
            'namespace' => static::getNamespace($data),
            'className' => static::normalizeClassName($data),
            'value' => ThrowableDto::from([
                'message' => $data->getMessage(),
                'code' => $data->getCode(),
                'file' => $data->getFile(),
                'line' => $data->getLine(),
                'trace' => $this->capsulizeBacktraceRecursively($this->sanitizeBacktrace($data->getTrace())),
                'previous' => $data->getPrevious(),
            ]),
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws ValueTypeException
     */
    private function getPropertiesToArray($data): array
    {
        $properties = [];

        $reflection = new \ReflectionClass($data);

        foreach ($reflection->getProperties() as $property) {
            $modifier = $property->getModifiers();

            $property->setAccessible(true);

            // modifier to string
            $modifier = \implode(' ', \Reflection::getModifierNames($modifier));

            $modifierAndPropertyName = ($modifier . self::MODIFIER_SPLITTER . $property->getName());

            $properties[$modifierAndPropertyName] = $property->getValue($data);
        }

        return \array_map(
             /** @throws ValueTypeException|ReflectionException */
             function ($item) {
                return $this->capsulizeRecursively($item);
            },
             $properties,
        );
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
}
