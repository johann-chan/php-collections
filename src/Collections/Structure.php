<?php

namespace Collections;

use Collections\Implementations\CollectionImplementationStruct;
use Collections\Map;
use RuntimeException;

/**
 * Structure is a Map with defined keys
 */
class Structure extends Map
{

    use CollectionImplementationStruct;

    /**
     * Authorized keys
     * @var array
     */
    protected $keys = [];

    /**
     * map used to avoid using in_array when checking duplicates
     * @var array
     */
    private $flip = [];

    /**
     * constructor
     * @param array $array
     * @param array $keys
     */
    public function __construct(array $array, array $keys)
    {
        array_walk($keys, function($k) {
            if(!is_string($k)) {
                throw new RuntimeException("Keys must only be string");
            }
        });
        $this->keys = $keys;
        $this->flip = array_flip($keys);
        $arrkeys = array_keys($array);
        if(!empty(array_diff($arrkeys, $keys)) || !empty(array_diff($keys, $arrkeys))) {
            throw new RuntimeException("keys missmatching");
        }
        parent::__construct($array);
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetSet($offset, $value)
    {
        if(!isset($this->flip[$offset])) {
            throw new RuntimeException("Can not modify the value corresponding to this key, as it is not defined in structure");
        }
        return parent::offsetSet($offset, $value);
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public function offsetUnset($offset)
    {
        throw new RuntimeException("Can not remove key from structure, as it will create mismatch in keys");
    }

}