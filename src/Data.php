<?php

namespace Beaverlabs\Gg;

use ReflectionException;

class Data implements \JsonSerializable
{
    /**
     * @return static
     *
     * @throws ReflectionException
     */
    public static function from(array $inputs): Data
    {
        $reflection = new \ReflectionClass(static::class);

        /** @var static $dataClass */
        $dataClass = $reflection->newInstanceWithoutConstructor();

        foreach ($inputs as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);

                $property->setValue($dataClass, $value);
            }
        }

        return $dataClass;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $result = [];

        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $value = $property->getValue($this);
            $result[$property->getName()] = $this->convertValue($value);
        }

        return $result;
    }

    protected static function convertValue(mixed $value): mixed
    {
        if ($value instanceof Data) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return array_map(static fn ($item) => self::convertValue($item), $value);
        }

        if ($value instanceof \UnitEnum) {
            return $value->value;
        }

        return $value;
    }
}
