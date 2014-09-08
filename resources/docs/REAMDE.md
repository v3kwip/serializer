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


### Nested objects

```php
<?php
use AndyTruong\Serializer\Unserializer;

$person_with_father = $unserializer->fromArray([
        'name' => 'Johnson English',
        'father' => ['name' => 'Johnson English']
    ]);
```

### Use Trait

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
