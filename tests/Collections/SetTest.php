<?php

namespace Collections\Tests;

use Collections\Set;
use Collections\Sequence;

class SetTest extends \PHPUnit_Framework_TestCase
{

    public function testToArray()
    {
        $array = ["a" => 1, 2, "c" => 3, 3 , 4, 1];
        $set = new Set($array);
        $this->assertEquals([1, 2, 3, 4], $set->toArray());
    }

    public function testJson()
    {
        $set = new Set([1, 2, 3]);
        $this->assertEquals("[1,2,3]", $set->toJSON());
    }

    public function testGetter()
    {
        $set = new Set([1, 2, 3]);
        $this->assertEquals(3, $set->get(2));
        $this->assertEquals(3, $set[2]); //equivalent
    }

    public function testSetter()
    {
        $set = new Set([1, 2, 3]);
        $set[] = 4;
        $set[2] = 4;
        $set->set(3, 5);
        $set["added"] = 6;
        $set["notadded"] = 5;
        $this->assertEquals([1, 2, 3, 5, 6], $set->toArray());
    }

    public function testSetterNull()
    {
        $set = new Set([null]);
        $set[] = null;
        $this->assertEquals([null], $set->toArray());
    }

    public function testUnsetter()
    {
        $set = new Set([1, 2, 4, 5, 6, 7]);
        $set[] = 5;
        unset($set[3]);
        $this->assertEquals([1, 2, 4, 6, 7], $set->toArray());
        $set[] = 5;
        $this->assertEquals([1, 2, 4, 6, 7, 5], $set->toArray());
    }

    public function testFilter()
    {
        $array = [1, 2, 3];
        $set = new Set($array);
        $filtered = $set->filter(function($_) {return ($_ & 1);});
        $this->assertTrue($filtered instanceof Set);
        $this->assertEquals($array, $set->toArray());
        $this->assertEquals(2, $filtered->count());
        $this->assertEquals(1, $filtered->get(0));
        $this->assertEquals(3, $filtered->get(1));
    }

    public function testFilterKey()
    {
        $array = [1, 2, 3];
        $set = new Set($array);
        $filtered = $set->filterKey(function($_) {return ($_ & 1);});
        $this->assertTrue($filtered instanceof Set);
        $this->assertEquals($array, $set->toArray()); //check original Map is not altered
        $this->assertEquals(1, $filtered->count());
        $this->assertEquals(2, $filtered->get(0));
    }

    public function testMap()
    {
        $array = [1, 2, 3];
        $set = new Set($array);
        $highLevelFunction = function($pow) {
            return function($item) use ($pow) {
                return pow($item, $pow);
            };
        };
        $filtered = $set->map($highLevelFunction(2));
        $this->assertTrue($filtered instanceof Set);
        $this->assertEquals($array, $set->toArray());
        $this->assertEquals(3, $filtered->count());
        $this->assertEquals(1, $filtered->get(0));
        $this->assertEquals(4, $filtered->get(1));
        $this->assertEquals(9, $filtered->get(2));
    }

    public function testConvertions()
    {
        $array = [1, 2, 3];
        $set = new Set($array);
        $this->assertEquals($array, $set->toSequence()->toArray());
    }

    public function testEmpty()
    {
        $stdClass1 = new \StdClass;
        $stdClass2 = new \StdClass;
        $set = new Set([1, 2, 3, $stdClass1, $stdClass1, $stdClass1, $stdClass2]);
        $this->assertFalse($set->isEmpty());
        $this->assertEquals(5, $set->count());
        $set = new Set([null]);
        $this->assertFalse($set->isEmpty());
        $this->assertTrue($set->filter(function($i) {return $i !== null;})->isEmpty());
    }

    public function testFoldLeft()
    {
        $set = new Set([1, 2, 3, 4, 5]);
        $this->assertEquals(120, $set->foldLeft(function($v, $acc) {return $acc === null ? $v : $acc * $v;}));
    }

    public function testFoldRight()
    {
        $set = new Set([1, 2, 3, 4, 5]);
        $this->assertEquals(120, $set->foldRight(function($v, $acc) {return $acc === null ? $v : $acc * $v;}));
    }

    public function testSort()
    {
        $array = [1, 2, 3, 4, 5];
        $set = new Set($array);
        $this->assertEquals([1, 2, 3, 4, 5], $set->sort()->toArray());
        $this->assertEquals($array, $set->toArray()); //check that original colection is unaltered
        $this->assertEquals([5, 4, 3, 2, 1], $set->sort(function($a, $b) {
            return ($a === $b) ? 0 : ($a > $b) ? -1 : 1; 
        })->toArray());
    }

    public function testImplode()
    {
        $set = new Set([1, 2, 3, 4, 5]);
        $this->assertEquals("1+2+3+4+5", $set->implode("+"));
        $this->assertEquals("5+4+3+2+1", $set->implode("+", true));
    }

    public function testSlice()
    {
        $set = new Set([1, 2, 3, 4, 5]);
        $this->assertEquals([2, 3, 4], $set->slice(1, -1)->toArray());
    }

    public function testSplice()
    {
        $set = new Set([1, 2, 3, 4, 5]);
        $this->assertEquals([1, 2, 4, 5], $set->splice(2)->toArray());
    }

    public function testIndexOf()
    {
        $set = new Set([1, 2, 3, 4, 5]);
        $this->assertEquals(2, $set->indexOf(3));
    }

    public function testOffsetExists()
    {
        $set = new Set([1, 2, 3, 4, 5]);
        $this->assertEquals(true, $set->offsetExists(4));
        $this->assertEquals(false, $set->offsetExists(5));
    }

    public function testMerge()
    {
        $set = new Set([1, 2, 3, 3]);
        $seq = new Sequence([4, 5, 6, 7, 4]);
        $set2 = $set->merge($seq->toSet());
        $this->assertTrue($set2 instanceof Set);
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7], $set2->toArray());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testMergeFail()
    {
        $set = new Set([1, 2, 3, 3]);
        $seq = new Sequence([4, 5, 6, 7]);
        $set2 = $set->merge($seq);
    }

}