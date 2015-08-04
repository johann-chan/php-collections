<?php

namespace Collections\Tests;

use Collections\ImmSequence;

class ImmSequenceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException RuntimeException
     */
    public function testSetterUpdate()
    {
        $seq = new ImmSequence([1, 2, 3]);
        $seq[2] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterInsert()
    {
        $seq = new ImmSequence([1, 2, 3]);
        $seq[] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnsetter()
    {
        $seq = new ImmSequence([1, 2, 3]);
        unset($seq[1]);
    }

}