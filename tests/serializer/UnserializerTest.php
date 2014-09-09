<?php

namespace AndyTruong\Serializer\TestCases\Services;

use AndyTruong\Serializer\Fixtures\Person;
use AndyTruong\Serializer\Unserializer;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Validator\Exception\RuntimeException;

/**
 * @group unserializer
 */
class UnserializerTest extends PHPUnit_Framework_TestCase
{

    public function testDemo()
    {
        $unserializer = new Unserializer();
        $person_array = array('name' => 'James T.', 'father' => array('name' => 'Andy T.'));
        $person = $unserializer->fromArray($person_array, 'AndyTruong\Serializer\Fixtures\Person');
        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\Person', $person);
        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\Person', $person->getFather());
        $this->assertEquals('James T.', $person->getName());
        $this->assertEquals('Andy T.', $person->getFather()->getName());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage AndyTruong\Serializer\Fixtures\Person.country is not writable.
     */
    public function testWrongCase()
    {
        $unserializer = new Unserializer();
        $person_array = array('name' => 'Matt T.', 'country' => 'Vietnam');
        $unserializer->fromArray($person_array, 'AndyTruong\Serializer\Fixtures\Person');
    }

    public function testAutoloading()
    {
        $unserializer = new Unserializer();

        // Mocking
        $em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $rp = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $father = new Person();
        $father->setName('Andy T.');

        $em->expects($this->any())->method('getRepository')->willReturn($rp);
        $rp->expects($this->any())->method('find')->willReturn($father);

        // Inject mock to unserializer
        $unserializer->setEntityManager($em);

        $person_array = array('name' => 'James T.', 'father' => 1);
        $person = $unserializer->fromArray($person_array, 'AndyTruong\Serializer\Fixtures\Person');
        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\Person', $person);
        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\Person', $person->getFather());
        $this->assertEquals('James T.', $person->getName());
        $this->assertEquals('Andy T.', $person->getFather()->getName());
    }

}
