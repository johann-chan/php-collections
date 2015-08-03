<?php

namespace Collections\Tests;

use Collections\Sequence;

class SequenceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test toArray method
     */
    public function testToArray()
    {
        $array = ["a" => 1, 2, "c" => 3];
        $seq = new Sequence($array);
        $this->assertEquals([1, 2, 3], $seq->toArray());
    }

    /**
     * test json encode
     */
    public function testJson()
    {
        $array = [1, 2, 3];
        $seq = new Sequence($array);
        $this->assertEquals("[1,2,3]", $seq->toJSON());
    }

    /**
     * test getter
     */
    public function testGetter()
    {
        $array = [1, 2, 3];
        $seq = new Sequence($array);
        $this->assertEquals(3, $seq->get(2));
        $this->assertEquals(3, $seq[2]); //equivalent
    }

    /**
     * test setter
     */
    public function testSetter()
    {
        $array = [1, 2, 3];
        $seq = new Sequence($array);
        $seq[2] = 4;
        $seq->set(3, 5);
        $seq["notCorrectKey"] = 6;
        $seq[] = 7;
        $this->assertEquals([1, 2, 4, 5, 6, 7], $seq->toArray());
    }

    /**
     * test unsetter
     */
    public function testUnsetter()
    {
        $array = [1, 2, 4, 5, 6, 7];
        $seq = new Sequence($array);
        unset($seq[3]);
        $this->assertEquals([1, 2, 4, 6, 7], $seq->toArray());
    }

    /**
     * test filter method
     */
    public function testFilter()
    {
        $array = [1, 2, 3];
        $seq = new Sequence($array);
        $filtered = $seq->filter(function($_) {return ($_ & 1);});
        $this->assertTrue($filtered instanceof Sequence);
        $this->assertEquals($array, $seq->toArray());
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
        $seq = new Sequence($array);
        $highLevelFunction = function($pow) {
            return function($item) use ($pow) {
                return pow($item, $pow);
            };
        };
        $filtered = $seq->map($highLevelFunction(2));
        $this->assertTrue($filtered instanceof Sequence);
        $this->assertEquals($array, $seq->toArray());
        $this->assertEquals(3, $filtered->count());
        $this->assertEquals(1, $filtered->get(0));
        $this->assertEquals(4, $filtered->get(1));
        $this->assertEquals(9, $filtered->get(2));
    }

    public function testConvertions()
    {
        $array = [1, 2, 3, 3];
        $seq = new Sequence($array);
        $this->assertEquals([1, 2, 3], $seq->toSet()->toArray());      
    }

}