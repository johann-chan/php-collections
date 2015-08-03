<?php

namespace Collections\Implementations;

trait IteratorImplementation
{

    /**
     * @see http://php.net/manual/en/class.iterator.php
     */
    public final function current()
    {
        return current($this->array);
    }

    /**
     * @see http://php.net/manual/en/class.iterator.php
     */
    public final function key()
    {
        return key($this->array);
    }

    /**
     * @see http://php.net/manual/en/class.iterator.php
     */
    public final function next()
    {
        return next($this->array);
    }

    /**
     * @see http://php.net/manual/en/class.iterator.php
     */
    public final function rewind()
    {
        return reset($this->array);
    }

    /**
     * @see http://php.net/manual/en/class.iterator.php
     */
    public final function valid()
    {
        return key($this->array) !== null;
    }

}