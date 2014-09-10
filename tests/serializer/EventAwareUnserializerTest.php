<?php

namespace AndyTruong\Serializer\TestCases;

use AndyTruong\Serializer\Event;
use AndyTruong\Serializer\Unserializer;
use PHPUnit_Framework_TestCase;

class EventAwareUnserializerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @group att
     */
    public function testEvents()
    {
        $unserializer = new Unserializer();
        $personArray = array('name' => 'James T.', 'father' => array('name' => 'Andy T.'));

        $unserializer->getDispatcher()->addListener('unserialize.array.after', function(Event $event) {
            /* @var Person  $person */
            $person = $event->getOutObject();
            $person->setName('Mr James');
        });

        $person = $unserializer->fromArray($personArray, 'AndyTruong\Serializer\Fixtures\Person');
        $this->assertEquals('Mr James', $person->getName());
    }

}
