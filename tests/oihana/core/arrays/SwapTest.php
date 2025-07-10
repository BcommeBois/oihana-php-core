<?php

namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

class SwapTest extends TestCase
{
    public function testSwapBasicCase()
    {
        $array = [1, 2, 3, 4];
        $result = swap($array, 1, 3);
        $this->assertEquals([1, 4, 3, 2], $result);
    }

    public function testSwapWithClone()
    {
        $array = [1, 2, 3, 4];
        $result = swap($array, 1, 3, true);
        $this->assertEquals([1, 4, 3, 2], $result);
    }

    public function testSwapWithSameIndex()
    {
        $array = [1, 2, 3, 4];
        $result = swap($array, 2, 2);
        $this->assertEquals([1, 2, 3, 4], $result);
    }

    public function testSwapWithOutOfBoundsIndices()
    {
        $array = [1, 2, 3, 4];
        $result = swap($array, 1, 10); // 10 est hors limites
        $this->assertEquals([1, 2, 3, 4], $result); // Doit rester inchangé
    }

    public function testSwapWithDefaultIndices()
    {
        $array = ['a', 'b', 'c'];
        $result = swap($array); // Utilise les indices par défaut (0, 0)
        $this->assertEquals(['a', 'b', 'c'], $result); // Pas de changement attendue
    }

    public function testSwapWithEmptyArray()
    {
        $array = [];
        $result = swap($array, 0, 1);
        $this->assertEquals([], $result);
    }

    public function testSwapWithAssociativeArray()
    {
        $array = ['a' => 1, 'b' => 2, 'c' => 3];
        $result = swap($array, 'a', 'c');
        $this->assertEquals(['a' => 3, 'b' => 2, 'c' => 1], $result);

        // Test avec clone=true
        $result = swap($array, 'a', 'c', true);
        $this->assertEquals(['a' => 3, 'b' => 2, 'c' => 1], $result);
    }

    public function testSwapWithNegativeIndices()
    {
        $array = [1, 2, 3, 4];
        $result = swap( $array , -1 , 0 ); // Dernier élément avec le premier
        $this->assertEquals([4, 2, 3, 1], $result);
    }
}