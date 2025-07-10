<?php
namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

class ShuffleTest extends TestCase
{
    public function testEmptyArray()
    {
        $array = [];
        $this->assertEquals([], shuffle($array));
    }

    public function testSingleElementArray() {
        $array = [1];
        $this->assertEquals([1], shuffle($array));
    }

    public function testShuffleArray() {
        $array = [1, 2, 3, 4, 5];
        $originalArray = $array;
        $shuffledArray = shuffle($array);

        // Check that the array is a permutation of the original
        sort($originalArray);
        sort($shuffledArray);
        $this->assertEquals($originalArray, $shuffledArray);
    }

    public function testModifyOriginalArray() {
        $array = [1, 2, 3, 4, 5];
        $isModified = false;
        $iterations = 100;

        for ($i = 0; $i < $iterations; $i++) {
            $originalArray = $array;
            shuffle($array);
            if ($array !== $originalArray) {
                $isModified = true;
                break;
            }
        }

        $this->assertTrue($isModified);
    }
}
