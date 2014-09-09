<?php

namespace AndyTruong\Serializer;

use ReflectionProperty;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{

    /** @var ReflectionProperty[] */
    private $properties;
    private $outArray;
    private $outObject;

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    function getProperties()
    {
        return $this->properties;
    }

    function getOutArray()
    {
        return $this->outArray;
    }

    function setOutArray($outArray)
    {
        $this->outArray = $outArray;
    }

    function getOutObject()
    {
        return $this->outObject;
    }

    function setOutObject($outObject)
    {
        $this->outObject = $outObject;
    }

}
