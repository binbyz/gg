<?php

namespace Beaverlabs\Gg;

class Data implements \JsonSerializable
{
    /**
     * @return static
     */
    public static function from(array $inputs): Data
    {
        $reflection = new \ReflectionClass(static::class);

        /** @var static $dataClass */
        $dataClass = $reflection->newInstanceWithoutConstructor();

        foreach ($inputs as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);

                $property->setAccessible(true);
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
