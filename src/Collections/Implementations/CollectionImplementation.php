<?php

namespace Collections\Implementations;

use Closure;

trait CollectionImplementation
{

    /**
     * return a native PHP array
     * @return Array
     */
    public final function toArray()
    {
        return $this->array;
    }

    /**
     * return new collection with elements satisfying $closure
     * @param Closure
     * @return Collection
     */
    public final function filter(Closure $closure)
    {
        return new static(array_filter($this->array, $closure));
    }

    /**
     * return new collection with applying $closure to each elements
     * @param Closure
     * @return Collection
     */
    public final function map(Closure $closure)
    {
        return new static(array_map($closure, $this->array));
    }

}