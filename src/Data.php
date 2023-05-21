<?php

namespace Beaverlabs\GG;

class Data implements \JsonSerializable
{
    /**
     * @param  array  $inputs
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
        $result = [];

        $reflection = new \ReflectionClass(static::class);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $result[$property->getName()] = $property->getValue($this);
        }

        return $result;
    }
}
