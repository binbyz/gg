<?php

namespace Beaverlabs\Gg;

use Beaverlabs\Gg\Data\DataCapsuleData;
use Beaverlabs\Gg\Data\LineCodeData;
use Beaverlabs\Gg\Data\MessageData;
use Beaverlabs\Gg\Data\ThrowableData;
use Beaverlabs\Gg\Enums\MessageType;
use ReflectionException;

class MessageHandler
{
    protected static array $skipHelperFunctions = [];

    protected static array $skipTraceClasses = [];

    protected static array $propertySortingClasses = [
    ];

    const int DEBUG_BACKTRACE_LIMIT = 50;

    const string ANONYMOUS_CLASS_PREFIX = '@anonymous';

    const string MODIFIER_SPLITTER = '@';

    private mixed $data;

    private MessageType $messageType;

    private function __construct($data, ?MessageType $messageType)
    {
        $this->data = $data;
        $this->messageType = is_null($messageType) ? self::guessMessageType($data) : $messageType;
    }

    public function getBacktrace(): array
    {
        $backtrace = ($this->isThrowableData())
            ? $this->data->getTrace()
            : debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, self::DEBUG_BACKTRACE_LIMIT);

        if ($this->isThrowableData()) {
            /** @var \Exception $proxy */
            $proxy = &$this->data;

            array_unshift($backtrace, [
                'file' => $proxy->getFile(),
                'line' => $proxy->getLine(),
            ]);
        }

        $backtrace = array_map(
            function ($row) {
                $row['sourceCode'] = array_key_exists('file', $row) && array_key_exists('line', $row)
                    ? $this->readCode($row['file'], $row['line'])
                    : [];

                if (array_key_exists('object', $row)) {
                    unset($row['object']);
                }

                return $row;
            },
            $backtrace,
        );

        return $this->capsulizeBacktraceRecursively($this->skipTrace($backtrace));
    }

    /**
     * @throws ReflectionException
     */
    public static function convert($data, ?MessageType $messageType = null, bool $debugBacktrace = false): MessageData
    {
        $self = new self($data, $messageType);

        return MessageData::from([
            'type' => $self->getMessageType(),
            'language' => 'PHP',
            'version' => phpversion(),
            'framework' => AgentDetector::detectFramework(),
            'data' => $self->capsulizeRecursively($self->getData()),
            'trace' => ($debugBacktrace) ? $self->getBacktrace() : [],
        ]);
    }

    /**
     * @return array<int, LineCodeData>
     */
    private function readCode(string $file, int $line, int $offset = 3): array
    {
        if (! file_exists($file) || ! is_readable($file)) {
            return [];
        }

        $handle = fopen($file, 'r');

        if (! $handle) {
            return [];
        }

        $code = [];
        $begin = $line - $offset;
        $end = $line + $offset;
        $currentLine = 1;

        while (! feof($handle)) {
            $currentLineContent = fgets($handle);

            if ($currentLine >= $begin && $currentLine <= $end) {
                $code[$currentLine] = $currentLineContent;
            }

            if ($currentLine > $end) {
                break;
            }

            $currentLine++;
        }

        fclose($handle);

        $result = [];
        foreach (array_filter($code) as $line => $code) {
            $result[] = LineCodeData::from([
                'line' => $line,
                'code' => $code,
            ]);
        }

        unset($code);

        return $result;
    }

    private static function guessMessageType($data): MessageType
    {
        if ($data instanceof \Throwable) {
            return MessageType::THROWABLE;
        }

        return MessageType::LOG;
    }

    public static function isScalar($data): bool
    {
        return is_scalar($data) || is_null($data);
    }

    public function capsulizeRecursively($data): DataCapsuleData
    {
        if ($data instanceof DataCapsuleData) {
            return $data;
        }

        if (is_resource($data)) {
            return $this->capsulizeScalar('resource#'.\get_resource_type($data));
        }

        if (static::isScalar($data)) {
            return $this->capsulizeScalar($data);
        }

        if (is_array($data)) {
            return $this->capsulizeArray($data);
        }

        if ($data instanceof \Throwable) {
            return $this->capsulizeThrowable($data);
        }

        return $this->capsulizeObject($data);
    }

    public function capsulizeBacktraceRecursively(array $backtrace): array
    {
        return array_map(
            function ($item) {
                if (array_key_exists('args', $item)) {
                    $item['args'] = $this->capsulizeRecursively($item['args']);
                }

                return $item;
            },
            $backtrace
        );
    }

    public function skipTrace(array $backtrace): array
    {
        $backtrace = array_filter($backtrace, static function (array $item) {
            if (array_key_exists('class', $item)) {
                foreach (self::$skipTraceClasses as $class) {
                    if (strpos($item['class'], $class) > -1) {
                        return false;
                    }
                }
            }

            if (array_key_exists('function', $item)) {
                foreach (self::$skipHelperFunctions as $helperFunction) {
                    if (strpos($item['function'], $helperFunction) > -1) {
                        return false;
                    }
                }
            }

            return true;
        });

        $backtrace = array_map(static function (array $item) {
            if (array_key_exists('args', $item) && \is_array($item['args'])) {
                $item['args'] = array_map(static function ($arg) {
                    if (is_object($arg)) {
                        return DataCapsuleData::from([
                            'type' => 'class',
                            'isScalar' => false,
                            'namespace' => static::getNamespace($arg),
                            'class' => static::normalizeNamespace($arg),
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

    private function capsulizeScalar($data): DataCapsuleData
    {
        return DataCapsuleData::from([
            'type' => gettype($data),
            'isScalar' => true,
            'value' => $data,
        ]);
    }

    public function capsulizeArray(array $data): DataCapsuleData
    {
        return DataCapsuleData::from([
            'type' => gettype($data),
            'isScalar' => false,
            'namespace' => null,
            'class' => null,
            'value' => \array_map(function ($item) {
                return $this->capsulizeRecursively($item);
            }, $data),
        ]);
    }

    public function capsulizeObject(object $data): DataCapsuleData
    {
        return DataCapsuleData::from([
            'type' => gettype($data),
            'isScalar' => false,
            'namespace' => static::getNamespace($data),
            'class' => static::normalizeNamespace($data),
            'value' => $this->getPropertiesToArray($data),
        ]);
    }

    public function capsulizeThrowable(\Throwable $data): DataCapsuleData
    {
        return DataCapsuleData::from([
            'type' => gettype($data),
            'isScalar' => false,
            'namespace' => static::getNamespace($data),
            'class' => static::normalizeNamespace($data),
            'value' => ThrowableData::from([
                'message' => $data->getMessage(),
                'code' => $data->getCode(),
                'file' => $data->getFile(),
                'line' => $data->getLine(),
                'previous' => $data->getPrevious(),
            ]),
        ]);
    }

    /**
     * @throws ReflectionException
     */
    private function getPropertiesToArray($data): array
    {
        $properties = [];

        $reflection = new \ReflectionClass($data);

        $existSortClass = in_array($reflection->getName(), array_keys(self::$propertySortingClasses), true);
        $availableProperties = $existSortClass !== false ? self::$propertySortingClasses[$reflection->getName()] : false;

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();

            if (is_array($availableProperties) && ! in_array($propertyName, $availableProperties, true)) {
                continue;
            }

            $modifier = $property->getModifiers();

            // modifier to string
            $modifier = implode(' ', \Reflection::getModifierNames($modifier));

            if (! str_starts_with($propertyName, '_')) {
                $modifierAndPropertyName = ($modifier.self::MODIFIER_SPLITTER.$propertyName);
                $properties[$modifierAndPropertyName] = $property->getValue($data);
            }
        }

        return array_map(
            function ($item) {
                return $this->capsulizeRecursively($item);
            },
            $properties,
        );
    }

    public static function getNamespace($data): string
    {
        $namespace = explode('\\', get_class($data));
        array_pop($namespace);

        return implode('\\', $namespace);
    }

    public static function normalizeNamespace($data): string
    {
        $class = get_class($data);

        if (str_contains($class, self::ANONYMOUS_CLASS_PREFIX)) {
            $exploded = explode(self::ANONYMOUS_CLASS_PREFIX, $class);

            $class = $exploded[0].self::ANONYMOUS_CLASS_PREFIX;
        }

        return $class;
    }

    public function isThrowableData(): bool
    {
        return $this->data instanceof \Throwable;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessageType(): MessageType
    {
        return $this->messageType;
    }
}
