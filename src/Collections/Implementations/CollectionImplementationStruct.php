<?php

namespace Collections\Implementations;

use Closure;

trait CollectionImplementationStruct
{

    /**
     * return new collection with elements satisfying $closure
     * @param Closure
     * @return Collection
     */
    public function filter(Closure $closure)
    {
        return new static(array_filter($this->array, $closure), $this->keys);
    }

    /**
     * return new collection with applying $closure to each elements
     * @param Closure
     * @return Collection
     */
    public function map(Closure $closure)
    {
        return new static(array_map($closure, $this->array), $this->keys);
    }

}