<?php

namespace Collections\Tests;

use Collections\Sequence;

class SequenceTest extends \PHPUnit_Framework_TestCase
{

    public function testToArray()
    {
        $array = ["a" => 1, 2, "c" => 3];
        $seq = new Sequence($array);
        $this->assertEquals([1, 2, 3], $seq->toArray());
    }

    public function testJson()
    {
        $seq = new Sequence([1, 2, 3]);
        $this->assertEquals("[1,2,3]", $seq->toJSON());
    }

    public function testGetter()
    {
        $seq = new Sequence([1, 2, 3]);
        $this->assertEquals(3, $seq->get(2));
        $this->assertEquals(3, $seq[2]); //equivalent
    }

    public function testSetter()
    {
        $seq = new Sequence([1, 2, 3]);
        $seq[2] = 4;
        $seq->set(3, 5);
        $seq["notCorrectKey"] = 6;
        $seq[] = 7;
        $this->assertEquals([1, 2, 4, 5, 6, 7], $seq->toArray());
    }

    public function testUnsetter()
    {
        $seq = new Sequence([1, 2, 3, 4, 5, 6]);
        unset($seq[3]);
        $this->assertEquals([1, 2, 3, 5, 6], $seq->toArray());
    }

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

    public function testFilterKey()
    {
        $array = [1, 2, 3];
        $seq = new Sequence($array);
        $filtered = $seq->filterKey(function($_) {return ($_ & 1);});
        $this->assertTrue($filtered instanceof Sequence);
        $this->assertEquals($array, $seq->toArray()); //check original Map is not altered
        $this->assertEquals(1, $filtered->count());
        $this->assertEquals(2, $filtered->get(0));
    }

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

    public function testEmpty()
    {
        $seq = new Sequence([1, 2, 3]);
        $this->assertFalse($seq->isEmpty());
        $seq = new Sequence([null]);
        $this->assertFalse($seq->isEmpty());
        $this->assertTrue($seq->filter(function($i) {return $i !== null;})->isEmpty());
    }

    public function testFoldLeft()
    {
        $seq = new Sequence([1, 2, 3, 4, 5]);
        $this->assertEquals(120, $seq->foldLeft(function($v, $acc) {return $acc === null ? $v : $acc * $v;}));
    }

    public function testFoldRight()
    {
        $seq = new Sequence([1, 2, 3, 4, 5]);
        $this->assertEquals(120, $seq->foldRight(function($v, $acc) {return $acc === null ? $v : $acc * $v;}));
    }

    public function testSort()
    {
        $array = [1, 2, 3, 4, 5];
        $seq = new Sequence($array);
        $this->assertEquals([1, 2, 3, 4, 5], $seq->sort()->toArray());
        $this->assertEquals($array, $seq->toArray()); //check that original colection is unaltered
        $this->assertEquals([5, 4, 3, 2, 1], $seq->sort(function($a, $b) {
            return ($a === $b) ? 0 : ($a > $b) ? -1 : 1; 
        })->toArray());
    }

    public function testImplode()
    {
        $seq = new Sequence([1, 2, 3, 4, 5]);
        $this->assertEquals("1+2+3+4+5", $seq->implode("+"));
        $this->assertEquals("5+4+3+2+1", $seq->implode("+", true));
    }

    public function testSlice()
    {
        $seq = new Sequence([1, 2, 3, 4, 5]);
        $this->assertEquals([2, 3, 4], $seq->slice(1, -1)->toArray());
    }

    public function testSplice()
    {
        $seq = new Sequence([1, 2, 3, 4, 5]);
        $this->assertEquals([1, 2, 4, 5], $seq->splice(2)->toArray());
    }

    public function testIndexOf()
    {
        $seq = new Sequence([1, 2, 3, 4, 5]);
        $this->assertEquals(2, $seq->indexOf(3));
    }

    public function testOffsetExists()
    {
        $seq = new Sequence([1, 2, 3, 4, 5]);
        $this->assertEquals(true, $seq->offsetExists(4));
        $this->assertEquals(false, $seq->offsetExists(5));

    }

}