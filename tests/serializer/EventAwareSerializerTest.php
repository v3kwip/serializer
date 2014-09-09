<?php

namespace AndyTruong\Serializer\TestCases;

use AndyTruong\Event\Event;
use AndyTruong\Serializer\Fixtures\Person;
use AndyTruong\Serializer\Serializer;
use PHPUnit_Framework_TestCase;
use ReflectionProperty;

class EventAwareSerializerTest extends PHPUnit_Framework_TestCase
{

    private function getPerson()
    {
        $person = new Person();
        $person->setName('Andy T.');
        return $person;
    }

    public function testEventAwareSerializer()
    {
        $logs = [];

        $serializer = new Serializer();
        $serializer->getDispatcher()->addListener('serialize.properties', function(Event $event) use (&$logs) {
            $logs[$event->getName()] = $event->getSubject();
        });
        $serializer->toArray($this->getPerson());

        /* @var $rname ReflectionProperty */
        $rname = $logs['serialize.properties'][0];
        $this->assertEquals('name', $rname->getName());
    }

}
