<?php

namespace Collections\Implementations;

trait JsonSerializableImplementation
{

    /**
     * @see http://php.net/manual/fr/class.jsonserializable.php
     */
    public final function jsonSerialize()
    {
        return $this->toJSON();
    }

}