<?php

namespace Beaverlabs\GG;

class Data
{
    /**
     * @param  array  $inputs
     * @return static
     */
    public static function from(array $inputs)
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
}
