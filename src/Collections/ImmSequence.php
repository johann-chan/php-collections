<?php

namespace Collections;

use Collections\Sequence;
use Collections\Immutable;

/**
 * Immutable Sequence, you can not add or remove entries
 * Sequence is like [1, 2, 3, 4, 5]
 */
class ImmSequence extends Sequence
{

	use Immutable;

}