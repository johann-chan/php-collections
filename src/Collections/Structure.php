<?php

namespace Collections;

use Collections\Collection;
use Collections\ImmMap;
use Collections\ImmSequence;
use Collections\ImmSet;
use Collections\Map;
use Collections\Sequence;
use Collections\Set;
use RuntimeException;

/**
 * Structure is ImmMap with defined keys
 */
class Structure extends Collection
{

    /**
     * @const string
     */
    const USE_KEYS = "keys";

    /**
     * @const string
     */
    const USE_VALUES = "values";

    /**
     * map used to avoid using in_array when checking duplicates
     * @var array
     */
    private $fields = [];

    /**
     * constructor
     * @param array $array
     * @param array $fields
     */
    public function __construct(array $array, array $fields)
    {
        $this->fields = array_flip($fields);
        $keys = array_keys($array);
        if(!empty(array_diff($fields, $keys)) || !empty(array_diff($keys, $fields))) {
            throw new RuntimeException("fields missmatching");
        }
        $this->array = $array;
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetSet($offset, $value)
    {
        if(!isset($this->fields[$offset])) {
            throw new RuntimeException("Can not modify the value corresponding to this key, as it is not defined in structure");
        }
        $this->array[$offset] = $value;
        return $this;
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetUnset($offset)
    {
        throw new RuntimeException("Can not remove key from structure, as it will create mismatch in fields");
    }

    /**
     * return new Sequence, base on keys or value of the map
     * @param string $use
     * @return Sequence
     */
    public function toSequence($use = self::USE_VALUES)
    {
        return new Sequence(($use === self::USE_KEYS) ? array_keys($this->array) : array_values($this->array));
    }

    /**
     * return new Set, base on keys or value of the map
     * @param string $use
     * @return Set
     */
    public function toSet($use = self::USE_VALUES)
    {
        return new Set(($use === self::USE_KEYS) ? array_keys($this->array) : array_values($this->array));
    }

    /**
     * return new Structure
     * @return Structure
     */
    public function toMap()
    {
        return new Map($this->array);
    }

    /**
     * return new Immutable Sequence, base on keys or value of the map
     * @param string $use
     * @return Sequence
     */
    public function toImmSequence($use = self::USE_VALUES)
    {
        return new ImmSequence(($use === self::USE_KEYS) ? array_keys($this->array) : array_values($this->array));
    }

    /**
     * return new Immutable Set, base on keys or value of the map
     * @param string $use
     * @return Set
     */
    public function toImmSet($use = self::USE_VALUES)
    {
        return new ImmSet(($use === self::USE_KEYS) ? array_keys($this->array) : array_values($this->array));
    }

    /**
     * return new Immutable Structure
     * @return Structure
     */
    public function toImmMap()
    {
        return new ImmMap($this->array);
    }

}