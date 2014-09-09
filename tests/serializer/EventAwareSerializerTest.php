<?php

namespace AndyTruong\Serializer\TestCases;

use AndyTruong\Serializer\Event;
use AndyTruong\Serializer\Fixtures\Person;
use AndyTruong\Serializer\Serializer;
use PHPUnit_Framework_TestCase;

class EventAwareSerializerTest extends PHPUnit_Framework_TestCase
{

    private function getPerson()
    {
        $father = new Person();
        $father->setName('Andy T.');

        $person = new Person();
        $person->setName('James T.');
        $person->setFather($father);

        return $person;
    }

    /**
     * @group at
     */
    public function testEventAwareSerializer()
    {
        $logs = [];

        $serializer = new Serializer();

        // Listen to reduce 'father' property in export list
        $serializer->getDispatcher()->addListener('serialize.properties', function(Event $event) use (&$logs) {
            $pties = $event->getProperties();
            foreach ($pties as $i => $item) {
                if ('father' === $item->getName()) {
                    unset($pties[$i]);
                }
            }
            $event->setProperties($pties);
            $logs[$event->getName()] = $event;
        });

        // Listen to 'serialize.array' to add 'year' to export data.
        $serializer->getDispatcher()->addListener('serialize.array', function(Event $event) use (&$logs) {
            $array = $event->getOutArray();
            $array['year'] = date('Y');
            $event->setOutArray($array);
        });

        // Action
        $array = $serializer->toArray($this->getPerson());

        // Check
        $this->assertArrayHasKey('serialize.properties', $logs);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('father', $array);
        $this->assertEquals(date('Y'), $array['year']);
    }

}
