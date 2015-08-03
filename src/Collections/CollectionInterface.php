<?php

namespace Collections;

use Closure;

interface CollectionInterface
{

    /**
     * return a native PHP array
     * @return Array
     */
    public function toArray();

    /**
     * return new CollectionInterface with elements satisfying $closure
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function filter(Closure $closure);

    /**
     * return new CollectionInterface with $closure applied to every elements
     * @param Closure $closure
     * @return CollectionInterface
     */
    public function map(Closure $closure);

}