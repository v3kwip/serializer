<?php

namespace AndyTruong\Serializer;

use ReflectionProperty;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{

    /** @var string */
    private $inClassName;

    /** @var array */
    private $inArray;

    /** @var object */
    private $inObj;

    /** @var ReflectionProperty[] */
    private $properties;

    /** @var array */
    private $outArray;

    /** @var object */
    private $outObject;

    function getInClassName()
    {
        return $this->inClassName;
    }

    function setInClassName($inClassName)
    {
        $this->inClassName = $inClassName;
    }

    function getInObj()
    {
        return $this->inObj;
    }

    function setInObj($inObj)
    {
        $this->inObj = $inObj;
    }

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

    function getInArray()
    {
        return $this->inArray;
    }

    function setInArray($inArray)
    {
        $this->inArray = $inArray;
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
