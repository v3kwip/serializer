<?php

namespace AndyTruong\Serializer;

use ReflectionClass;
use ReflectionProperty;

/**
 * Trait provide fromArray() and toArray methods, simple way for serialization.
 */
trait SerializableTrait
{

    /**
     * Get a property in object.
     *
     * @param string $pty
     * @return mixed
     */
    protected function getPropertyValue($pty, $includeNull, $maxNesting)
    {
        $return = $this->{$pty};

        $camelPty = at_camelize($pty);
        $rClass = new ReflectionClass($this);
        foreach (array('get', 'is', 'has') as $prefix) {
            $method = $prefix . $camelPty;
            if ($rClass->hasMethod($method) && $rClass->getMethod($method)->isPublic() && !count($rClass->getMethod($method)->getParameters())) {
                $return = $this->{$method}();
                break;
            }
        }

        if (is_object($return) && method_exists($return, 'toArray')) {
            if (($this !== $return) && ($maxNesting > 0)) {
                $return = $return->toArray($includeNull, $maxNesting - 1);
            }
        }

        return $return;
    }

    /**
     * @return ReflectionProperty[]
     */
    protected function getReflectionProperties()
    {
        return (new ReflectionClass($this))->getProperties();
    }

    /**
     * Represent object as array.
     *
     * @param boolean $includeNull
     * @param int $maxNesting
     * @return array
     */
    public function toArray($includeNull = false, $maxNesting = 3)
    {
        $array = array();

        foreach ($this->getReflectionProperties() as $pty) {
            /* @var $pty ReflectionProperty */
            if ($pty->isStatic()) {
                continue;
            }

            $value = $this->getPropertyValue($pty->getName(), $includeNull, $maxNesting);
            if ((null !== $value) || ((null === $value) && $includeNull)) {
                $array[$pty->getName()] = $value;
            }
        }

        return $array;
    }

    /**
     * Represent object in json format.
     *
     * @param boolean $includeNull
     * @param int $options
     * @param int $maxNesting
     * @return string
     */
    public function toJSON($includeNull = false, $options = 0, $maxNesting = 3)
    {
        return json_encode($this->toArray($includeNull, $maxNesting), $options);
    }

}
