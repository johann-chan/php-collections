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
    public function testConstructExceptionI()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ImmStructure($array, ["a", "c"]);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testConstructExceptionII()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ImmStructure($array, ["a", "d"]);
    }

    /**
     * test toArray method
     */
    public function testToArray()
    {
        $array = ["a" => 1, "b" => 2, "c" => null];
        $struct = new ABCImmStruct($array);
        $this->assertEquals($array, $struct->toArray());
    }

    /**
     * test json encode
     */
    public function testJson()
    {
        $array = ["a" => 1, "b" => 2, "c" => null];
        $struct = new ABCImmStruct($array);
        $this->assertEquals('{"a":1,"b":2,"c":null}', $struct->toJSON());
    }

    /**
     * test getter
     */
    public function testGetter()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ABCImmStruct($array);
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
        $struct = new ABCImmStruct($array);
        $struct["d"] = 4;
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterUpdate()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ABCImmStruct($array);
        $struct["c"] = 4;
        $struct->c = 5;
        $this->assertEquals(5, $struct->get("c"));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testUnsetter()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ABCImmStruct($array);
        unset($struct["c"]);
    }

    //return exception because 2 is filtered, so new map does not have "b" key
    /**
     * @expectedException RuntimeException
     */
    public function testFilter()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ABCImmStruct($array);
        $filtered = $struct->filter(function($_) {return ($_ & 1);});
    }

    /**
     * test map method
     */
    public function testMap()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new ABCImmStruct($array);
        $highLevelFunction = function($pow) {
            return function($item) use ($pow) {
                return pow($item, $pow);
            };
        };
        $filtered = $struct->map($highLevelFunction(2));
        $this->assertTrue($filtered instanceof ImmStructure);
        $this->assertEquals($array, $struct->toArray());
        $this->assertEquals(3, $filtered->count());
        $this->assertEquals(1, $filtered->get("a"));
        $this->assertEquals(4, $filtered->get("b"));
        $this->assertEquals(9, $filtered->get("c"));
    }

    public function testConvertions()
    {
        $array = ["a" => 1, "b" => 2, "c" => 2];
        $struct = new ABCImmStruct($array);
        $this->assertEquals($array, $struct->toMap()->toArray());
        $this->assertEquals([1, 2, 2], $struct->toSequence()->toArray()); 
        $this->assertEquals(["a", "b", "c"], $struct->toSequence(ImmStructure::USE_KEYS)->toArray()); 
        $this->assertEquals([1, 2], $struct->toSet()->toArray()); 
        $this->assertEquals(["a", "b", "c"], $struct->toSet(ImmStructure::USE_KEYS)->toArray());        
    }

}