<?php

namespace Collections\Tests;

use Collections\ImmMap;

class ImmMapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException RuntimeException
     */
    public function testSetterUpdate()
    {
        $map = new ImmMap(["a" => 1, "b" => 2, "c" => 3]);
        $map["c"] = 5;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterInsert()
    {
        $map = new ImmMap(["a" => 1, "b" => 2, "c" => 3]);
        $map["d"] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnsetter()
    {
        $map = new ImmMap(["a" => 1, "b" => 2, "c" => 3]);
        unset($map["a"]);
    }

}