<?php

namespace Collections;

use Closure;
use Collections\Collection;
use Collections\ImmSequence;
use Collections\Implementations\CollectionImplementation;
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
        list($array, $flip) = $this->unique($array);
        $this->flip = $flip;
        parent::__construct($array);
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetSet($offset, $value)
    {
        if(!$this->inArray($value)){
            $this->flip[$this->toString($value)] = true;
            parent::offsetSet($offset, $value);
        }
        //if value already exist we just ignore it
        return $this;
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetUnset($offset)
    {
        unset($this->flip[$this->toString($this->array[$offset])]);
        parent::offsetUnset($offset);
        return $this;
    }

    /**
     * return a string identifier for any values that are not integer or string
     * @param mixed $value;
     * @return string
     */
    protected function toString($value)
    {
        if(is_object($value)) {
            $value = spl_object_hash($value);
        }
        if(is_array($value)) {
            $value = md5(serialize($value));
        }
        return $value;
    }

    /**
     * check if value is already in Collection
     * @param array $array
     * @return array
     */
    private function inArray($value)
    {
        return isset($this->flip[$this->toString($value)]);
    }

    /**
     * filter array removing duplicate entries, unlike array_unique this work with array and objects
     * @param array $array
     * @return array
     */
    private function unique(array $array)
    {
        $flip = [];
        $filtered = array_filter($array, Closure::Bind(function($value) use (&$flip) {
            $value = $this->toString($value);
            if(isset($flip[$value])) {
                return false;
            }
            $flip[$value] = true;
            return true;
        }, $this, $this));
        return [$filtered, $flip];
    }

}