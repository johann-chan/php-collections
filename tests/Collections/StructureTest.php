<?php

namespace Collections\Tests;

use Collections\Structure;

/**
 * for less verbose code
 */
class ABCStruct extends Structure
{
    public function __construct(array $array) {parent::__construct($array, ["a", "b", "c"]);}
}

class StructureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException RuntimeException
     */
    public function testConstructExceptionI()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new Structure($array, ["a", "c"]);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testConstructExceptionII()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new Structure($array, ["a", "d"]);
    }

    public function testToArray()
    {
        $array = ["a" => 1, "b" => 2, "c" => null];
        $struct = new ABCStruct($array);
        $this->assertEquals($array, $struct->toArray());
    }

    public function testJson()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => null]);
        $this->assertEquals('{"a":1,"b":2,"c":null}', $struct->toJSON());
    }

    public function testGetter()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals(3, $struct->get("c"));
        $this->assertEquals(3, $struct["c"]);
        $this->assertEquals(3, $struct->c);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterInsert()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ABCStruct($array);
        $struct["d"] = 4;
    }

    public function testSetterUpdate()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $struct["c"] = 4;
        $struct->c = 5;
        $this->assertEquals(5, $struct->get("c"));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnsetter()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        unset($struct["c"]);
    }

    //return exception because 2 is filtered, so new map does not have "b" key
    /**
     * @expectedException RuntimeException
     */
    public function testFilter()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $filtered = $struct->filter(function($_) {return ($_ & 1);});
    }

    public function testMap()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new Structure($array, ["a", "b", "c"]);
        $highLevelFunction = function($pow) {
            return function($item) use ($pow) {
                return pow($item, $pow);
            };
        };
        $filtered = $struct->map($highLevelFunction(2));
        $this->assertTrue($filtered instanceof Structure);
        $this->assertEquals($array, $struct->toArray());
        $this->assertEquals(3, $filtered->count());
        $this->assertEquals(1, $filtered->get("a"));
        $this->assertEquals(4, $filtered->get("b"));
        $this->assertEquals(9, $filtered->get("c"));
    }

    public function testConvertions()
    {
        $array = ["a" => 1, "b" => 2, "c" => 2];
        $struct = new ABCStruct($array);
        $this->assertEquals($array, $struct->toMap()->toArray());
        $this->assertEquals([1, 2, 2], $struct->toSequence()->toArray());
        $this->assertEquals(["a", "b", "c"], $struct->toSequence(Structure::USE_KEYS)->toArray());
        $this->assertEquals([1, 2], $struct->toSet()->toArray());
        $this->assertEquals(["a", "b", "c"], $struct->toSet(Structure::USE_KEYS)->toArray());
    }

}