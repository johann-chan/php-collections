<?php

namespace Collections\Tests;

use Collections\ImmStructure;

/**
 * for less verbose code
 */
class ABCImmStruct extends ImmStructure
{
    public function __construct(array $array) {parent::__construct($array, ["a", "b", "c"]);}
}

class ImmStructureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException RuntimeException
     */
    public function testSetterUpdate()
    {
        $struct = new ABCImmStruct(["a" => 1, "b" => 2, "c" => 3]);
        $struct["c"] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterInsert()
    {
        $struct = new ABCImmStruct(["a" => 1, "b" => 2, "c" => 3]);
        $struct["d"] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnsetter()
    {
        $struct = new ABCImmStruct(["a" => 1, "b" => 2, "c" => 3]);
        unset($struct["c"]);
    }

    //return exception because 2 is filtered, so new map does not have "b" key
    /**
     * @expectedException RuntimeException
     */
    public function testFilter()
    {
        $struct = new ABCImmStruct(["a" => 1, "b" => 2, "c" => 3]);
        $filtered = $struct->filter(function($_) {return ($_ & 1);});
    }

}