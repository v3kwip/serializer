<?php

namespace AndyTruong\Serializer\TestCases\Traits;

use AndyTruong\Serializer\Fixtures\SerializablePerson;
use PHPUnit_Framework_TestCase;

/**
 * @group entitytrait
 */
class SerializableTraitTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        parent::setUp();

        if (-1 === version_compare(phpversion(), '5.4')) {
            $this->markTestSkipped('Trait is only available in PHP 5.4');
        }
    }

    public function testFromToArray()
    {
        $father = SerializablePerson::fromArray(array('name' => 'Andy T'));
        $person = SerializablePerson::fromArray(array('name' => 'James T', 'father' => $father));

        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\SerializablePerson', $father);
        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\SerializablePerson', $person);

        $this->assertEquals(array(
            'name' => 'James T',
            'father' => array('name' => 'Andy T')
            ), $person->toArray()
        );

        $this->assertEquals(array(
            'name' => 'James T',
            'father' => array('name' => 'Andy T')
            ), $person->toArray(false)
        );
    }

    public function testFromToJSON()
    {
        $andyt = '{ "name": "Andy T" }';
        $jamest = '{ "name": "James T", "father": { "name": "Andy T" } }';

        $father = SerializablePerson::fromJSON($andyt);
        $person = SerializablePerson::fromJSON($jamest);

        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\SerializablePerson', $father);
        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\SerializablePerson', $person);
        $this->assertInstanceOf('AndyTruong\Serializer\Fixtures\SerializablePerson', $person->getFather());

        $this->assertJsonStringEqualsJsonString($andyt, $father->toJSON(false));
        $this->assertJsonStringEqualsJsonString($jamest, $person->toJSON(false));
    }

}
