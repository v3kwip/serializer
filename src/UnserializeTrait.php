<?php

namespace AndyTruong\Serializer;

use ReflectionClass;
use RuntimeException;

trait UnserializeTrait
{

    use \AndyTruong\Event\EventAwareTrait;

    /**
     * Set property.
     *
     * @param string $pty
     * @param mixed $value
     * @throws \RuntimeException
     */
    public function setPropertyValue($pty, $value)
    {
        $method = 'set' . at_camelize($pty);
        $rClass = new ReflectionClass($this);

        if ($rClass->hasMethod($method) && $rClass->getMethod($method)->isPublic()) {
            if (is_array($value) && $typeHint = $rClass->getMethod($method)->getParameters()[0]->getClass()) {
                if (method_exists($typeHint->getName(), 'fromArray')) {
                    $value = call_user_func([$typeHint->getName(), 'fromArray'], $value);
                }
            }
            $this->{$method}($value);
        }
        elseif ($rClass->hasProperty($pty) && $rClass->getProperty($pty)->isPublic()) {
            $this->{$pty} = $value;
        }
        else {
            throw new RuntimeException(sprintf('Object.%s is not writable.', $pty));
        }
    }

    /**
     * Simple fromArray factory.
     *
     * @param array $input
     * @return self
     */
    public static function fromArray($input)
    {
        $me = new static();
        foreach ($input as $pty => $value) {
            if (null !== $value) {
                $me->setPropertyValue($pty, $value);
            }
        }
        return $me;
    }

    /**
     * Create new object from json string.
     *
     * @param string $input
     * @return static
     */
    public static function fromJSON($input)
    {
        return static::fromArray(json_decode($input, true));
    }

}
