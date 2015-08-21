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

    /**
     * @expectedException RuntimeException
     */
    public function testConstructExceptionIII()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $struct = new Structure($array, ["a", new \stdClass]);
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

    /**
     * @expectedException RuntimeException
     */
    public function testFilterKey()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $filtered = $struct->filterKey(function($_) {return $_ === "a";});
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

    public function testEmpty()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertFalse($struct->isEmpty());
    }

    public function testFoldLeft()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals("123", $struct->foldLeft(function($v, $acc) {return (string) $acc . (string) $v;}));
    }

    public function testFoldRight()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals("321", $struct->foldRight(function($v, $acc) {return (string) $acc . (string) $v;}));
    }

    public function testSort()
    {
        $array = ["c" => 8, "a" => -1, "b" => 0];
        $struct = new ABCStruct($array);
        $this->assertEquals(["a" => -1, "b" => 0, "c" => 8], $struct->sort()->toArray());
        $this->assertEquals($array, $struct->toArray()); //check that original colection is unaltered
        $this->assertEquals(["c" => 8, "b" => 0, "a" => -1], $struct->sort(function($a, $b) {
            return ($a === $b) ? 0 : ($a > $b) ? -1 : 1; 
        })->toArray());
    }

    public function testKeySort()
    {
        $struct = new Structure(["d" => 1, "e" => 2, "b" => 3], ["e", "d", "b"]);
        $this->assertEquals(["b" => 3, "d" => 1, "e" => 2], $struct->keySort()->toArray());
    }

    public function testImplode()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals("1+2+3", $struct->implode("+"));
        $this->assertEquals("3+2+1", $struct->implode("+", true));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSlice()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals(["b" => 2, "c" => 3], $struct->slice(1, -1)->toArray());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSplice()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals(["b" => 2, "c" => 3], $struct->splice(0)->toArray());
    }


    public function testIndexOf()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals("b", $struct->indexOf(2));
        $this->assertEquals("c", $struct->indexOf(3));
    }

    public function testOffsetExists()
    {
        $struct = new ABCStruct(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals(true, $struct->offsetExists("c"));
    }

}