<?php

namespace AndyTruong\Serializer\TestCases\Services;

use AndyTruong\Serializer\Fixtures\Person;
use AndyTruong\Serializer\Serializer;
use PHPUnit_Framework_TestCase;

/**
 * @group serializer
 */
class SerializerTest extends PHPUnit_Framework_TestCase
{

    public function testDemo()
    {
        $serializer = new Serializer();

        $father = new Person();
        $father->setName('Andy T.');

        $person = new Person();
        $person->setName('James T.');
        $person->setFather($father);

        $expected = array('name' => 'James T.', 'father' => array('name' => 'Andy T.'));
        $this->AssertEquals($expected, $serializer->toArray($person));
    }

}
