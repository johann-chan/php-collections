<?php

namespace Collections\Tests;

use Collections\Map;
use Collections\Sequence;

class MapTest extends \PHPUnit_Framework_TestCase
{

    public function testToArray()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new Map($array);
        $this->assertEquals($array, $map->toArray());
    }

    public function testJson()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals('{"a":1,"b":2,"c":3}', $map->toJSON());
    }

    public function testGetter()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3]);
        $this->assertEquals(3, $map->get("c"));
        $this->assertEquals(3, $map["c"]);
        $this->assertEquals(3, $map->c);
    }

    public function testSetter()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3]);
        $map["d"] = 4;
        $map->c = 5;
        $map->set("a", 6);
        $this->assertEquals(4, $map->count());
        $this->assertEquals(4, $map->get("d"));
        $this->assertEquals(5, $map->get("c"));
        $this->assertEquals(6, $map->get("a"));
    }

    public function testFilter()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new Map($array);
        $filtered = $map->filter(function($_) {return ($_ & 1);});
        $this->assertTrue($filtered instanceof Map);
        $this->assertEquals($array, $map->toArray()); //check original Map is not altered
        $this->assertEquals(2, $filtered->count());
        $this->assertEquals(1, $filtered->get("a"));
        $this->assertEquals(3, $filtered->get("c"));
    }

    public function testFilterKey()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new Map($array);
        $filtered = $map->filterKey(function($_) {return $_ === "a";});
        $this->assertTrue($filtered instanceof Map);
        $this->assertEquals($array, $map->toArray()); //check original Map is not altered
        $this->assertEquals(1, $filtered->count());
        $this->assertEquals(1, $filtered->get("a"));
    }

    public function testMap()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3];
        $map = new Map($array);
        $highLevelFunction = function($pow) {
            return function($item) use ($pow) {
                return pow($item, $pow);
            };
        };
        $filtered = $map->map($highLevelFunction(2));
        $this->assertTrue($filtered instanceof Map);
        $this->assertEquals($array, $map->toArray());
        $this->assertEquals(3, $filtered->count());
        $this->assertEquals(1, $filtered->get("a"));
        $this->assertEquals(4, $filtered->get("b"));
        $this->assertEquals(9, $filtered->get("c"));
    }

    public function testConvertions()
    {
        $array = ["a" => 1, "b" => 2, "c" => 3, "d" => 3];
        $map = new Map($array);
        $this->assertEquals($array, $map->toStructure()->toArray());
        $this->assertEquals([1, 2, 3, 3], $map->toSequence()->toArray());
        $this->assertEquals(["a", "b", "c", "d"], $map->toSequence(Map::USE_KEYS)->toArray());
        $this->assertEquals([1, 2, 3], $map->toSet()->toArray());
        $this->assertEquals(["a", "b", "c", "d"], $map->toSet(Map::USE_KEYS)->toArray());
    }

    public function testEmpty()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertFalse($map->isEmpty());
        $map = new Map(["a" => null]);
        $this->assertFalse($map->isEmpty());
        $map = new Map([]);
        $this->assertTrue($map->isEmpty());
    }

    public function testFoldLeft()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertEquals("1233", $map->foldLeft(function($v, $acc) {return (string) $acc . (string) $v;}));
    }

    public function testFoldRight()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertEquals("3321", $map->foldRight(function($v, $acc) {return (string) $acc . (string) $v;}));
    }

    public function testSort()
    {
        $array = ["d" => 8, "e" => -1, "b" => 0];
        $map = new Map($array);
        $this->assertEquals(["e" => -1, "b" => 0, "d" => 8], $map->sort()->toArray());
        $this->assertEquals($array, $map->toArray()); //check that original colection is unaltered
        $this->assertEquals(["d" => 8, "b" => 0, "e" => -1], $map->sort(function($a, $b) {
            return ($a === $b) ? 0 : ($a > $b) ? -1 : 1; 
        })->toArray());
    }

    public function testKeySort()
    {
        $map = new Map(["d" => 1, "e" => 2, "b" => 3]);
        $this->assertEquals(["b" => 3, "d" => 1, "e" => 2], $map->keySort()->toArray());
    }

    public function testImplode()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertEquals("1+2+3+3", $map->implode("+"));
        $this->assertEquals("3+3+2+1", $map->implode("+", true));
    }

    public function testSlice()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertEquals(["b" => 2, "c" => 3], $map->slice(1, -1)->toArray());
    }

    public function testSplice()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertEquals(["a" => 1, "d" => 3], $map->splice(1, 2)->toArray());
    }

    public function testIndexOf()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertEquals("b", $map->indexOf(2));
        $this->assertEquals("c", $map->indexOf(3));
    }

    public function testOffsetExists()
    {
        $map = new Map(["a" => 1, "b" => 2, "c" => 3, "d" => 3]);
        $this->assertEquals(true, $map->offsetExists("c"));
    }

    public function testMerge()
    {
        $map1 = new Map(["a" => 1, "b" => 2]);
        $map2 = new Map(["c" => 3, "d" => 3]);
        $map3 = $map1->merge($map2);
        $this->assertTrue($map3 instanceof Map);
        $this->assertEquals(["a" => 1, "b" => 2, "c" => 3, "d" => 3], $map3->toArray());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testMergeFail()
    {
        $map1 = new Map(["a" => 1, "b" => 2]);
        $seq = new Sequence(["c" => 3, "d" => 3]);
        $map1->merge($seq);
    }

}