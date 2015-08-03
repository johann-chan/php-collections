<?php

namespace Collections;

use Collections\Sequence;

/**
 * Mutable Set
 * Set is a sequence with unique entries
 */
class Set extends Sequence
{

    /**
      * map used to avoid using in_array when checking duplicates
     * @var array
     */
    private $flip = [];

    /**
     * constructor
     * @param array $array
     */
    public function __construct(array $array)
    {
        parent::__construct(array_unique($array));
        $this->flip = array_flip($this->array);
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetSet($offset, $value)
    {
        if(!isset($this->flip[$value])) {
            parent::offsetSet($offset, $value);
            $this->flip = array_flip($this->array);
        }
        //if value already exist we just ignore it
        return $this;
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetUnset($offset)
    {
        parent::offsetUnset($offset);
        $this->flip = array_flip($this->array);
        return $this;
    }

    /**
     * return new Sequence, base on keys or value of the map
     * @param string $use
     * @return Set
     */
    public function toSequence()
    {
        return new Set($this->array);
    }

    /**
     * return new Immutable Sequence, base on keys or value of the map
     * @param string $use
     * @return Set
     */
    public function toImmSequence()
    {
        return new ImmSet($this->array);
    }

}