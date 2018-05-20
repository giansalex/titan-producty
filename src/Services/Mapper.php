<?php
/**
 * Created by PhpStorm.
 * User: Atenea
 * Date: 20/05/2018
 * Time: 9:55
 */

namespace App\Services;

/**
 * Class Mapper
 */
class Mapper
{
    public function map($object, $class)
    {
        $properties = $this->getProperties($class);

        return $this->mapObject($object, $class, $properties);
    }

    public function mapArray($collection, $class)
    {
        $properties = iterator_to_array($this->getProperties($class));
        $items = [];

        foreach ($collection as $object) {
            $items[] = $this->mapObject($object, $class, $properties);
        }

        return $items;
    }

    private function getProperties($class)
    {
        $object = new $class();
        $props = get_object_vars($object);

        foreach ($props as $key => $value) {
            yield $key;
        }
    }

    private function mapObject($sourceObject, $targetClass, $properties)
    {
        $obj = new $targetClass();
        foreach ($properties as $prop) {
            $obj->{$prop} = $this->getProperty($sourceObject, $prop);
        }

        return $obj;
    }

    private function getProperty($target, $property)
    {
        $method = 'get'.$property;

         return call_user_func([$target, $method]);
    }
}