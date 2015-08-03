<?php

namespace Collections\Tests;

use Collections\Set;

class SetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test toArray method
     */
    public function testToArray()
    {
        $array = ["a" => 1, 2, "c" => 3, 3 , 4, 1];
        $set = new Set($array);
        $this->assertEquals([1, 2, 3, 4], $set->toArray());
    }

    /**
     * test json encode
     */
    public function testJson()
    {
        $array = [1, 2, 3];
        $set = new Set($array);
        $this->assertEquals("[1,2,3]", $set->toJSON());
    }

    /**
     * test getter
     */
    public function testGetter()
    {
        $array = [1, 2, 3];
        $set = new Set($array);
        $this->assertEquals(3, $set->get(2));
        $this->assertEquals(3, $set[2]); //equivalent
    }

    /**
     * test setter
     */
    public function testSetter()
    {
        $array = [1, 2, 3];
        $set = new Set($array);
        $set[] = 4;
        $set[2] = 4;
        $set->set(3, 5);
        $set["added"] = 6;
        $set["notadded"] = 5;
        $this->assertEquals([1, 2, 3, 5, 6], $set->toArray());
    }

    /**
     * test unsetter
     */
    public function testUnsetter()
    {
        $array = [1, 2, 4, 5, 6, 7];
        $set = new Set($array);
        $set[] = 5;
        unset($set[3]);
        $this->assertEquals([1, 2, 4, 6, 7], $set->toArray());
        $set[] = 5;
        $this->assertEquals([1, 2, 4, 6, 7, 5], $set->toArray());
    }

    /**
     * test filter method
     */
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

    /**
     * test map method
     */
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

}