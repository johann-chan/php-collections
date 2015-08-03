<?php

namespace Collections;

use Collections\Collection;
use Collections\ImmSequence;
use Collections\ImmSet;
use Collections\ImmStructure;
use Collections\Sequence;
use Collections\Set;
use Collections\Structure;

/**
 * Mutable hashmap
 * map is like ["a" => 1, "b" => 2]
 */
class Map extends Collection
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
     * constructor
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
        return $this;
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
        return $this;
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
    public function toStructure()
    {
    	return new Structure($this->array, array_keys($this->array));
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
    public function toImmStructure()
    {
    	return new ImmStructure($this->array, array_keys($this->array));
    }

}