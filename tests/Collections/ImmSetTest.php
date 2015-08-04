<?php

namespace Collections\Tests;

use Collections\ImmSet;

class ImmSetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException RuntimeException
     */
    public function testSetterUpdate()
    {
        $set = new ImmSet([1, 2, 3]);
        $set[2] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterInsert()
    {
        $set = new ImmSet([1, 2, 3]);
        $set[] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnsetter()
    {
        $set = new ImmSet([1, 2, 3]);
        unset($set[1]);
    }

}