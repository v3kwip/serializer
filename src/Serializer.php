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

    /**
     * Camelizes a given string.
     *
     * @param  string $string Some string
     *
     * @return string The camelized version of the string
     */
    protected function camelize($string)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $string);
    }

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

        $camelPty = $this->camelize($pty);
        $rClass = new ReflectionClass($obj);

        // property is public
        if (property_exists($obj, $pty) && $rClass->getProperty($pty)->isPublic()) {
            $return = $obj->{$pty};
        }
        else {
            foreach (array('get', 'is', 'has') as $prefix) {
                $method = $prefix . $camelPty;
                if ($rClass->hasMethod($method) && $rClass->getMethod($method)->isPublic()) {
                    $return = $obj->{$method}();
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

    /**
     * Convert object to array.
     *
     * @param stdClass $obj
     * @param bool $includeNull
     * @param int $maxNesting
     * @return array
     */
    public function toArray($obj, $includeNull = false, $maxNesting = 3)
    {
        $array = array();

        $rClass = new ReflectionClass($obj);
        foreach ($rClass->getProperties() as $pty) {
            /* @var $pty ReflectionProperty */
            if ($pty->isStatic()) {
                continue;
            }

            $value = $this->getPropertyValue($obj, $pty->getName(), $includeNull, $maxNesting);
            if ((null !== $value) || (null === $value && $includeNull)) {
                $array[$pty->getName()] = $value;
            }
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
    public function toJSON($obj, $includeNull = false, $maxNesting = 3)
    {
        return json_encode($this->toArray($obj, $includeNull, $maxNesting));
    }

}
