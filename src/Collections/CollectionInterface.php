<?php

namespace Collections;

use Closure;

interface CollectionInterface
{

    /**
     * return new CollectionInterface with elements satisfying $closure
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function filter(Closure $closure);

    /**
     * return new CollectionInterface with keys satisfying $closure
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function filterKey(Closure $closure);

    /**
     * return first element of collection
     * @return mixed
     */
    public function first();

    /**
     * apply closure to every element from left to right
     * @param Closure $closure
     * @return mixed
     */
    public function foldLeft(Closure $closure);

    /**
     * apply closure to every element from right to left
     * @param Closure $closure
     * @return mixed
     */
    public function foldRight(Closure $closure);

    /**
     * return string
     * @param string $string
     * @param boolean $reverse
     * @return string
     */
    public function implode($string, $reverse = false);

    /**
     * return the index or key of the value corresponding to $needle, false if not found
     * @return boolean
     */
    //public function indexOf($needle);

    /**
     * return if collection is empty
     * @return boolean
     */
    public function isEmpty();

    /**
     * return last element of collection
     * @return mixed
     */
    public function last();

    /**
     * return new CollectionInterface with $closure applied to every elements
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function map(Closure $closure);

    /**
     * return CollectionInterface
     * @param integer $offset
     * @param integer $limit
     * @return CollectionInterface
     */
    //public function slice($offset, $limit);

    /**
     * return new sorted CollectionInterface
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function sort(Closure $closure = null);

    /**
     * return a native PHP array
     * @return Array
     */
    public function toArray();

}