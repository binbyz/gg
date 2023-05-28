<?php

namespace Beaverlabs\GG;

use Beaverlabs\GG\Dto\DataCapsuleDto;
use Beaverlabs\GG\Dto\MessageDto;
use Beaverlabs\GG\Exceptions\ValueTypeException;
use ReflectionException;

class MessageHandler
{
    const SANITIZE_HELPER_FUNCTION = 'gg';
    const SANITIZE_BACKTRACE_NAMESPACES = [
        'Beaverlabs\\GG',
        'Illuminate\Support\Traits',
    ];

    const DEBUG_BACKTRACE_LIMIT = 500;
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

        $backtrace = debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, self::DEBUG_BACKTRACE_LIMIT);

        return MessageDto::from([
            'language' => 'PHP',
            'version' => \phpversion(),
            'framework' => static::detectFramework(),
            'data' => $self->capsulizeRecursively($self->data),
            'backtrace' => $self->sanitizeBacktrace(
                $self->capsulizeBacktraceRecursively($backtrace)
            )
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
        if (static::isScalarType($data)) {
            return $this->capsuleScalarType($data);
        }

        if (\is_array($data)) {
            return $this->capsuleArrayType($data);
        }

        return $this->capsuleObjectType($data);
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
                foreach (self::SANITIZE_BACKTRACE_NAMESPACES as $namespace) {
                    if (\strpos($item['class'], $namespace) > -1) {
                        return false;
                    }
                }
            }

            if (\array_key_exists('function', $item) && $item['function'] == self::SANITIZE_HELPER_FUNCTION) {
                return false;
            }

            return true;
        });

        return \array_values($backtrace);
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
     * @throws ReflectionException
     */
    public function capsuleArrayType($data): DataCapsuleDto
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
    public function capsuleObjectType($data): DataCapsuleDto
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
             $properties
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
