<?php

namespace Collections;

use RuntimeException;

trait Immutable
{

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public final function offsetSet($offset, $value)
    {
        throw new RuntimeException("Can not modify Immutable collection");
    }

    /**
     * @see http://php.net/manual/fr/class.arrayaccess.php
     */
    public final function offsetUnset($offset)
    {
        throw new RuntimeException("Can not modify Immutable collection");
    }

}