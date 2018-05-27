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
    /**
     * Map to object from class.
     *
     * @param object $object
     * @param string $class
     * @return mixed
     */
    public function map($object, string $class)
    {
        $properties = $this->getProperties($class);

        return $this->mapNewObject($object, $class, $properties);
    }

    /**
     * Map to existing object.
     *
     * @param object $source
     * @param object $target
     * @return mixed
     */
    public function mapToObject($source, $target)
    {
        $properties = $this->getProperties(get_class($target));

        return $this->applyObject($source, $target, $properties);
    }

    /**
     * Map to array objects from class.
     *
     * @param $collection
     * @param string $class
     * @return array
     */
    public function mapArray($collection, string $class)
    {
        $properties = $this->getProperties($class);
        $items = [];

        foreach ($collection as $object) {
            $items[] = $this->mapNewObject($object, $class, $properties);
        }

        return $items;
    }

    private function mapNewObject($sourceObject, $targetClass, $properties)
    {
        $obj = new $targetClass();

        return $this->applyObject($sourceObject, $obj, $properties);
    }

    private function applyObject($source, $target, $properties)
    {
        foreach ($properties as $prop) {
            $value = $this->getProperty($source, $prop);
            $this->setValue($target, $prop, $value);
        }

        return $target;
    }

    private function getProperty($target, $property)
    {
        $method = 'get'.$property;

         return call_user_func([$target, $method]);
    }

    private function setValue($target, $property, $value): void
    {
        if (property_exists($target, $property)) {
            $target->{$property} = $value;
            return;
        }

        $method = 'set'.$property;
        if (method_exists($target, $method)) {
            call_user_func_array([$target, $method], [$value]);
        }
    }

    private function getProperties($class)
    {
        $fields = $this->getFields($class);
        $setters = $this->getSetters($class);

        return array_merge(iterator_to_array($fields), iterator_to_array($setters));
    }

    private function getFields($class)
    {
        $object = new $class();
        $props = get_object_vars($object);

        foreach ($props as $key => $value) {
            yield $key;
        }
    }

    private function getSetters($class)
    {
        $methods = get_class_methods($class);

        foreach ($methods as $method) {
            if (substr($method, 0, 3) === 'set') {
                yield substr($method, 3);
            }
        }
    }
}