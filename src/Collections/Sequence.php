<?php

namespace Collections;

use Collections\Collection;

/**
 * Mutable Sequence
 * Sequence is like [1, 2, 3, 4, 5]
 */
class Sequence extends Collection
{

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
     * return new Set, base on keys or value of the map
     * @param string $use
     * @return Set
     */
    public function toSet()
    {
        return new Set($this->array);
    }

    /**
     * return new Immutable Set, base on keys or value of the map
     * @param string $use
     * @return Set
     */
    public function toImmSet()
    {
        return new ImmSet($this->array);
    }

}