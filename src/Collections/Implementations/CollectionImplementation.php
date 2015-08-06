<?php

namespace Collections\Implementations;

use Closure;

trait CollectionImplementation
{

    /**
     * return new collection with elements satisfying $closure
     * @param Closure
     * @return Collection
     */
    public function filter(Closure $closure)
    {
        return new static(array_filter($this->array, $closure));
    }

    /**
     * return new CollectionInterface with keys satisfying $closure
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function filterKey(Closure $closure)
    {
        return new static(array_filter($this->array, $closure, ARRAY_FILTER_USE_KEY));
    }

    /**
     * apply closure to every element from left to right
     * @param Closure $closure
     * @return mixed
     */
    public function foldLeft(Closure $closure)
    {
        $accumulator = null;
        foreach($this->array as &$v) {
            $accumulator = $closure($v, $accumulator);
        }
        unset($v);
        return $accumulator;
    }

    /**
     * apply closure to every element from right to left
     * @param Closure $closure
     * @return mixed
     */
    public function foldRight(Closure $closure)
    {
        $accumulator = null;
        foreach(array_reverse($this->array) as $v) {
            $accumulator = $closure($v, $accumulator);
        }
        unset($v);
        return $accumulator;
    }

    /**
     * return if collection is empty
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->array);
    }

    /**
     * return new collection with applying $closure to each elements
     * @param Closure
     * @return Collection
     */
    public function map(Closure $closure)
    {
        return new static(array_map($closure, $this->array));
    }

    /**
     * return a native PHP array
     * @return Array
     */
    public function toArray()
    {
        return $this->array;
    }

}