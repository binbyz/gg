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

        $reflection = new \ReflectionClass(static::class);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $value = $property->getValue($this);

            if ($value instanceof Data) {
                $value = $value->toArray();
            }

            if (is_array($value)) {
                $value = array_map(function ($item) {
                    if ($item instanceof Data) {
                        return $item->toArray();
                    }

                    if (is_array($item)) {
                        return array_map(function ($subItem) {
                            if ($subItem instanceof Data) {
                                return $subItem->toArray();
                            }

                            return $subItem;
                        }, $item);
                    }

                    return $item;
                }, $value);
            }

            if ($value instanceof \UnitEnum) {
                $value = $value->value;
            }

            $result[$property->getName()] = $value;
        }

        return $result;
    }
}
