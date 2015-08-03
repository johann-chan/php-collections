<?php

namespace Collections\Implementations;

trait CountableImplementation
{

    /**
     * @see http://php.net/manual/fr/class.countable.php
     */
    public final function count()
    {
        return count($this->array);
    }

}