<?php

namespace Collections;

use Collections\Collection;
use Collections\ImmSequence;
use Collections\ImmSet;
use Collections\Implementations\CollectionImplementation;
use Collections\set;
use Collections\Sequence;

/**
 * Mutable Sequence
 * Sequence is like [1, 2, 3, 4, 5]
 */
class Sequence extends Collection
{

    use CollectionImplementation;

    /**
     * constructor
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = array_values($array);
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetSet($offset, $value)
    {
        if(is_integer($offset)) {
            $this->array[$offset] = $value;
        } else {
            $this->array[] = $value;
        }
        return $this;
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
        $this->array = array_values($this->array);
        return $this;
    }

    /**
     * return new Sequence
     * @return Sequence
     */
    public function toSequence()
    {
        return new Sequence($this->array);
    }

    /**
     * return new Set
     * @return Set
     */
    public function toSet()
    {
        return new Set($this->array);
    }

    /**
     * return new Immutable Sequence
     * @return ImmSequence
     */
    public function toImmSequence()
    {
        return new ImmSequence($this->array);
    }

    /**
     * return new Immutable Set
     * @return ImmSet
     */
    public function toImmSet()
    {
        return new ImmSet($this->array);
    }

}