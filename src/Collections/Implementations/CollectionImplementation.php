<?php

namespace Collections\Implementations;

use Closure;
use RuntimeException;

trait CollectionImplementation
{

    /**
     * return new collection with elements satisfying $closure
     * @param Closure
     * @return Collection
     */
    public function filter(Closure $closure)
    {
        return $this->newInstance(array_filter($this->array, $closure));
    }

    /**
     * return new CollectionInterface with keys satisfying $closure
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function filterKey(Closure $closure)
    {
        return $this->newInstance(array_filter($this->array, $closure, ARRAY_FILTER_USE_KEY));
    }

    /**
     * return first element of collection
     * @return mixed
     */
    public function first()
    {
        return reset($this->array);
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
        return $accumulator;
    }

    /**
     * return string
     * @param string $string
     * @return string
     */
    public function implode($string, $reverse = false)
    {
        $method = $reverse ? "foldRight" : "foldLeft";
        return $this->$method(function($v, $acc) use ($string) {
            return $acc === null ? $v : $acc . $string . (string) $v;
        });
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
     * return last element of collection
     * @return mixed
     */
    public function last()
    {
        return end($this->array);
    }

    /**
     * return new collection with applying $closure to each elements
     * @param Closure
     * @return Collection
     */
    public function map(Closure $closure)
    {
        return $this->newInstance(array_map($closure, $this->array));
    }

    /**
     * return new sorted CollectionInterface
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function sort(Closure $closure = null)
    {
        $clone = $this->array;
        if($closure !== null) {
            $bool = uasort($clone, $closure);
        } else {
            $bool = asort($clone);
        }
        if(!$bool) {
            throw new RuntimeException("sort failed");
        }
        return $this->newInstance($clone);
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