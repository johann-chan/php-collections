<?php

namespace Collections;

use Collections\Map;
use Collections\Immutable;

/**
 * Immutable hashmap, you can not add or remove entries
 * map is like ["a" => 1, "b" => 2]
 */
class ImmMap extends Map
{

	use Immutable;

}