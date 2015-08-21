<?php

namespace Collections\Implementations;

trait ArrayAccessImplementation
{

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public final function offsetExists($offset)
    {
        return array_key_exists($offset, $this->array);
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public final function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    abstract public function offsetSet($offset, $value);

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    abstract public function offsetUnset($offset);

}