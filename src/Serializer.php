<?php

namespace AndyTruong\Serializer;

use ReflectionClass;
use ReflectionProperty;
use stdClass;

/**
 * Class to serialize an object to arary/json.
 *
 * use AndyTruong\Common\Serializer;
 * $obj = new Something();
 * print_r((new Serializer())->toArray($obj));
 *
 * @see \AndyTruong\Common\TestCases\Services\SerializerTest
 */
class Serializer
{

    use \AndyTruong\Event\EventAwareTrait;

    /**
     * Get value of an object using has|is|get method.
     *
     * @param stdClass $obj
     * @param string $pty
     * @param boolean $includeNull
     * @return mixed
     */
    protected function getPropertyValue($obj, $pty, $includeNull, $maxNesting)
    {
        $return = null;

        $camelPty = at_camelize($pty);
        $rClass = new ReflectionClass($obj);

        // property is public
        if (property_exists($obj, $pty) && $rClass->getProperty($pty)->isPublic()) {
            $return = $obj->{$pty};
        }
        else {
            foreach (array('get', 'is', 'has') as $prefix) {
                $method = $prefix . $camelPty;
                if ($rClass->hasMethod($method) && $rClass->getMethod($method)->isPublic() && !count($rClass->getMethod($method)->getParameters())) {
                    $return = $obj->{$method}();
                    break;
                }
            }
        }

        if (is_object($return)) {
            if (($this !== $return) && ($maxNesting > 0)) {
                $return = $this->toArray($return, $includeNull);
            }
        }

        return $return;
    }

    protected function getObjectPropertyReflections($obj)
    {
        $rClass = new ReflectionClass($obj);
        $rProperties = $rClass->getProperties();

        if ($rcParent = $rClass->getParentClass()) {
            $rProperties = array_merge($rProperties, $rcParent->getProperties());
        }

        if ($this->hasDispatcher()) {
            $event = new Event();
            $event->setInObj($obj);
            $event->setProperties($rProperties);
            $this->dispatch('serialize.properties', $event);
            return $event->getProperties();
        }
        return $rProperties;
    }

    /**
     * Convert object to array.
     *
     * @param stdClass $obj
     * @param bool $includeNull
     * @param int $maxNesting
     * @return array
     */
    public function toArray($obj, $includeNull = false, $maxNesting = 3, $dispatch = true)
    {
        $array = array();

        foreach ($this->getObjectPropertyReflections($obj) as $pty) {
            /* @var $pty ReflectionProperty */
            if ($pty->isStatic()) {
                continue;
            }

            $value = $this->getPropertyValue($obj, $pty->getName(), $includeNull, $maxNesting);
            if ((null !== $value) || (null === $value && $includeNull)) {
                $array[$pty->getName()] = $value;
            }
        }

        if ($dispatch && $this->hasDispatcher()) {
            $event = new Event();
            $event->setOutArray($array);
            $this->dispatch('serialize.array', $event);
            return $event->getOutArray();
        }

        return $array;
    }

    /**
     * Represent object in json format.
     *
     * @param stdClass $obj
     * @param bool $includeNull
     * @param int $maxNesting
     * @return string
     */
    public function toJSON($obj, $includeNull = false, $maxNesting = 3, $dispatch = true)
    {
        $return = json_encode($this->toArray($obj, $includeNull, $maxNesting, false));

        if ($dispatch && $this->hasDispatcher()) {
            $event = new Event();
            $event->setOutArray($return);
            $this->dispatch('serialize.json', $event);
            return $event->getOutArray();
        }

        return $return;
    }

}
