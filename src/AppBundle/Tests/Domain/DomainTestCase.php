<?php

namespace AppBundle\Tests\Domain;

use ReflectionClass;
use Exception;
use PHPUnit_Framework_TestCase;

abstract class DomainTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Allows us to execute private methods out of an object
     */
    protected function invokeMethod(
        &$object,
        $methodName,
        array $parameters = array()
    ) {
        $method = $this->setMethodAccessible($object, $methodName);
        return $method->invokeArgs($object, $parameters);
    }

    private function setMethodAccessible(&$object, $methodName)
    {
        $reflection = new ReflectionClass(get_class($object));
        if (!$reflection->hasMethod($methodName)) {
            throw new Exception('Method not found.');
        }
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Sets/Gets a private property from a given object
     */
    protected function setValue(&$object, $propertyName, $value)
    {
        $property = $this->setPropertyAccessible($object, $propertyName);

        return $property->setValue($object, $value);
    }

    private function setPropertyAccessible(&$object, $propertyName)
    {
        $reflection = new ReflectionClass(get_class($object));
        if (!$reflection->hasProperty($propertyName)) {
            throw new Exception('Property not found.');
        }
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }

    protected function getValue(&$object, $propertyName)
    {
        $property = $this->setPropertyAccessible($object, $propertyName);
        return $property->getValue($object);
    }
}