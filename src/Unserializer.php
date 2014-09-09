<?php

namespace AndyTruong\Serializer;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use RuntimeException;
use stdClass;

/**
 * Class to unserialize an object from arary/json.
 *
 *      class Person {
 *          private $name;
 *          private $father;
 *
 *          public function getName() { return $this->name; }
 *          public function getFather() { return $this->father; }
 *          public function setName($name) { $this->name = $name; }
 *          public function setFather(Person $father) { $this->father = $father; }
 *      }
 *
 *      use AndyTruong\Common\Unserializer;
 *      $unserializer = new Unserialize();
 *
 *      // Example 1: nested unserialize
 *      $person_1_array = ['name' => 'Joshep', 'father' => ['name' => 'Jacob']];
 *      $person_1 = $unserialize->fromArray($person_1_array, 'Person');
 *
 *      // Example 2: Lazy loading
 *      $unserializer->setEntityManager($em);
 *      $person_2_array = ['name' => 'Jacob', 'father' => 1];
 *      $person_2 = $unserializer->fromArray($person_2_array, 'Person');
 *
 * @see \AndyTruong\Common\TestCases\Services\SerializerTest
 */
class Unserializer
{

    use \AndyTruong\Event\EventAwareTrait;

    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Set entity manager.
     *
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Check existence of entity manager.
     *
     * @return boolean
     */
    public function hasEntityManager()
    {
        return null !== $this->em;
    }

    /**
     * Camelizes a given string.
     *
     * @param  string $string Some string
     * @return string The camelized version of the string
     */
    protected function camelize($string)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $string);
    }

    /**
     * Set property.
     *
     * @param string $pty
     * @param mixed $value
     * @throws \RuntimeException
     */
    public function setPropertyValue($obj, $pty, $value)
    {
        $method = 'set' . at_camelize($pty);
        $rClass = new ReflectionClass($obj);

        if ($rClass->hasMethod($method) && $rClass->getMethod($method)->isPublic()) {
            // object's property should be an other object, cast it here
            if (($params = $rClass->getMethod($method)->getParameters()) && $params[0]->getClass()) {
                try {
                    $value = $this->convertPropertyValue($value, $params[0]->getClass()->getName());
                }
                catch (\RuntimeException $e) {
                    throw new \RuntimeException(sprintf('Can not unserialize %s.%s', get_class($obj), $pty));
                }
            }

            // Inject value to object's property
            $obj->{$method}($value);
        }
        elseif ($rClass->hasProperty($pty) && $rClass->getProperty($pty)->isPublic()) {
            $obj->{$pty} = $value;
        }
        else {
            throw new RuntimeException(sprintf('%s.%s is not writable.', get_class($obj), $pty));
        }
    }

    /**
     *
     * @param int|array $in
     * @param string $toType
     * @return mixed
     */
    protected function convertPropertyValue($in, $toType)
    {
        if ($this->hasEntityManager() && is_numeric($in)) {
            if ($repository = $this->getEntityManager()->getRepository($toType)) {
                return $repository->find($in);
            }
        }

        if (is_array($in)) {
            return $this->fromArray($in, $toType);
        }

        throw new \RuntimeException();
    }

    /**
     * Convert array to object.
     *
     * @param array $array
     * @param string $className
     * @return stdClass
     */
    public function fromArray($array, $className)
    {
        $obj = new $className;
        foreach ($array as $pty => $value) {
            $this->setPropertyValue($obj, $pty, $value);
        }
        return $obj;
    }

    /**
     * Convert json string to object.
     *
     * @param string $json
     * @param string $className
     */
    public function fromJSON($json, $className)
    {
        return $this->fromArray(json_decode($json), $className);
    }

}
