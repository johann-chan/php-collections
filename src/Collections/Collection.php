<?php

namespace Collections;

use ArrayAccess;
use Collections\CollectionInterface;
use Collections\Implementations\ArrayAccessImplementation;
use Collections\Implementations\CollectionImplementation;
use Collections\Implementations\CountableImplementation;
use Collections\Implementations\IteratorImplementation;
use Collections\Implementations\JsonSerializableImplementation;
use Countable;
use Iterator;
use JsonSerializable;

abstract class Collection implements ArrayAccess, CollectionInterface, Countable, Iterator, JsonSerializable
{

    use ArrayAccessImplementation
      , CollectionImplementation
      , CountableImplementation
      , IteratorImplementation
      , JsonSerializableImplementation;

    /**
     * @var array
     */
    protected $array;

    /**
     * getter
     * @param string $name
     * @return mixed
     */
    public final function get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * setter
     * @param string $name
     * @param mixed $value
     * @return this
     */
    public final function set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    /**
     * magic getter
     * @param string $name
     * @return mixed
     */
    public final function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * magic setter
     * @param string $name
     * @param mixed $value
     * @return this
     */
    public final function __set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    /**
     * return a JSON object
     * @return Array
     */
    public final function toJSON()
    {
        return json_encode($this->array);
    }

}