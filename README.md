Common
======

[![Build Status](https://api.travis-ci.org/andytruong/serializer.svg?branch=v0.1)](https://travis-ci.org/andytruong/serializer) [![Latest Stable Version](https://poser.pugx.org/andytruong/serializer/v/stable.png)](https://packagist.org/packages/andytruong/serializer) [![Dependency Status](https://www.versioneye.com/php/andytruong:serializer/2.3.0/badge.svg)](https://www.versioneye.com/php/andytruong:serializer/2.3.0) [![License](https://poser.pugx.org/andytruong/serializer/license.png)](https://packagist.org/packages/andytruong/serializer)

Very simple Serializer/Unserializer for PHP objects.

If we have this very simple class:

```php
<?php
class Person {
    private $name;
    private $father;
    public function getName() { return $this->name; }
    public function getFather() { return $this->father; }
    public function setName($name) { $this->name = $name; }
    public function setFather(Person $father) { $this->father = $father; }
}
```

Then we can easy create new Person object from a structured array:

```php
<?php
use AndyTruong\Serializer\Serializer;

$serializer = new Serializer();

$person = new Person();
$person->setName('Johnson American');
$serializer->toArray($person); // ['name' => 'Johnson American']

$pperson = new Person();
$pperson->setName('Johnson English');
$person->setFather($pperson);
$serializer->toArray($person); // ['name' => 'Johnson American', 'father' => ['name' => 'Johnson English']]
```

We can also easy create new Person object from a structured array:

```php
<?php
use AndyTruong\Serializer\Unserializer;

$unserializer = new Unserializer();

// Create new Person object from array
$person = $unserializer->fromArray(['name' => 'Johnson America']);

// Nested object
$person_with_father = $unserializer->fromArray([
        'name' => 'Johnson English',
        'father' => ['name' => 'Johnson English']
    ]);
```

From PHP 5.4, we can use this Trait:

```php
<?php
class NewPerson extends Person {
  use \AndyTruong\Serializer\SerializableTrait;
}

// Unserialize
$person = NewPerson::fromArray([
  'name' => 'Johnson English',
  'father' => ['name' => 'Johnson America']
]);

// Serialize
$person->toArray();
```
