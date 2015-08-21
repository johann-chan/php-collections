<?php

namespace Collections\Implementations;

use Closure;
use Collections\Collection;
use RuntimeException;

trait CollectionImplementation
{

    /**
     * return new CollectionInterface with elements satisfying $closure
     * @param Closure
     * @return CollectionInterface
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
     * return the index or key of the value corresponding to $needle, false if not found
     * @param mixed $needle
     * @return mixed
     */
    public function indexOf($needle)
    {
        return array_search($needle, $this->array, true);
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
     * return new CollectionInterface with applying $closure to each elements
     * @param Closure
     * @return CollectionInterface
     */
    public function map(Closure $closure)
    {
        return $this->newInstance(array_map($closure, $this->array));
    }

    /**
     * return new CollectionInterface, merging 2 collections of same types
     * @param CollectionInterface $collection
     * @return CollectionInterface
     */
    public function merge(Collection $collection)
    {
        if(get_class($collection) !== static::class) {
            throw new RuntimeException("Can only merge collections of same type");
        }
        return $this->newInstance(array_merge($this->array, $collection->toArray()));
    }

    /**
     * return CollectionInterface
     * @param integer $offset
     * @param integer $limit
     * @return CollectionInterface
     */
    public function slice($offset, $limit)
    {
        return $this->newInstance(array_slice($this->array, $offset, $limit));
    }

    /**
     * return CollectionInterface
     * @param integer $offset
     * @param integer $length
     * @return CollectionInterface
     */
    public function splice($offset, $length = 1)
    {
        $clone = $this->array;
        array_splice($clone, $offset, $length);
        return $this->newInstance($clone);
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