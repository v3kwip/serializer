<?php

namespace AndyTruong\Serializer\Fixtures;

class SerializablePerson
{

    use \AndyTruong\Serializer\SerializableTrait;

    /**
     * Name of Person.
     *
     * @var string
     */
    protected $name;

    /**
     * Reference to Father.
     * @var SerializablePerson
     */
    protected $father;

    public function getName()
    {
        return $this->name;
    }

    public function getFather()
    {
        return $this->father;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setFather(SerializablePerson $father)
    {
        $this->father = $father;
        return $this;
    }

}
