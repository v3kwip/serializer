Serializer [![Build Status](https://api.travis-ci.org/andytruong/serializer.svg?branch=v0.1)](https://travis-ci.org/andytruong/serializer) [![Latest Stable Version](https://poser.pugx.org/andytruong/serializer/v/stable.png)](https://packagist.org/packages/andytruong/serializer) [![License](https://poser.pugx.org/andytruong/serializer/license.png)](https://packagist.org/packages/andytruong/serializer)
======

Very simple Serializer/Unserializer for PHP objects.

If we have this very simple class:

```php
<?php
class Person {
    private $name;
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
}
```

Then we can easy create new Person object from a structured array:

```php
<?php
$person = new Person();
$person->setName('Johnson American');
(new AndyTruong\Serializer\Serializer())
    ->toArray($person); // ['name' => 'Johnson American']
```

We can also easy create new Person object from a structured array:

```php
<?php
$person = (new AndyTruong\Serializer\Unserializer())
    ->fromArray(['name' => 'Johnson America']);
```

The library also supports Trait, nested objects, … check ./resources/docs for
more informations.
