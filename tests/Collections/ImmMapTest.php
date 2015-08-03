<?php

namespace Collections\Tests;

use Collections\ImmMap;

class ImmMapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test toArray method
     */
    public function testToArray()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new ImmMap($array);
        $this->assertEquals($array, $map->toArray());
    }

    /**
     * test json encode
     */
    public function testJson()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new ImmMap($array);
        $this->assertEquals('{"a":1,"b":2,"c":3}', $map->toJSON());
    }

    /**
     * test getter
     */
    public function testGetter()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new ImmMap($array);
        $this->assertEquals(3, $map->get("c"));
        $this->assertEquals(3, $map["c"]);
        $this->assertEquals(3, $map->c);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterUpdate()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new ImmMap($array);
        $map["c"] = 5;
        $this->assertEquals(5, $map->get("c"));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSetterInsert()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new ImmMap($array);
        $map["d"] = 4;
        $this->assertEquals(4, $map->count());
        $this->assertEquals(4, $map->get("d"));
    }

    /**
     * test filter method
     */
    public function testFilter()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new ImmMap($array);
        $filtered = $map->filter(function($_) {return ($_ & 1);});
        $this->assertTrue($filtered instanceof ImmMap);
        $this->assertEquals($array, $map->toArray());
        $this->assertEquals(2, $filtered->count());
        $this->assertEquals(1, $filtered->get("a"));
        $this->assertEquals(3, $filtered->get("c"));
    }

    /**
     * test map method
     */
    public function testMap()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new ImmMap($array);
        $highLevelFunction = function($pow) {
            return function($item) use ($pow) {
                return pow($item, $pow);
            };
        };
        $filtered = $map->map($highLevelFunction(2));
        $this->assertTrue($filtered instanceof ImmMap);
        $this->assertEquals($array, $map->toArray());
        $this->assertEquals(3, $filtered->count());
        $this->assertEquals(1, $filtered->get("a"));
        $this->assertEquals(4, $filtered->get("b"));
        $this->assertEquals(9, $filtered->get("c"));
    }

    public function testConvertions()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3, "d" => 3];
        $map = new ImmMap($array);
        $this->assertEquals($array, $map->toStructure()->toArray());
        $this->assertEquals([1, 2, 3, 3], $map->toSequence()->toArray()); 
        $this->assertEquals(["a", "b", "c", "d"], $map->toSequence(ImmMap::USE_KEYS)->toArray()); 
        $this->assertEquals([1, 2, 3], $map->toSet()->toArray()); 
        $this->assertEquals(["a", "b", "c", "d"], $map->toSet(ImmMap::USE_KEYS)->toArray());        
    }

}