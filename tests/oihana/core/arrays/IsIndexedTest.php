<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class IsIndexedTest extends TestCase
{
    public function testReturnsTrueForAnEmptyArray(): void
    {
        $this->assertTrue( isIndexed([]) );
    }

    public function testReturnsTrueForConsecutiveNumericKeys(): void
    {
        $this->assertTrue(isIndexed(['a', 'b', 'c']));
        $this->assertTrue(isIndexed([0 => 'a', 1 => 'b', 2 => 'c']));
        $this->assertTrue(isIndexed(['0' => 'a', '1' => 'b']));
    }

    public function testReturnsFalseForNonConsecutiveNumericKeys(): void
    {
        $this->assertFalse(isIndexed([0 => 'a', 2 => 'b'])); // Manque la clé 1
        $this->assertFalse(isIndexed([1 => 'a', 0 => 'b'])); // Ordre incorrect
        $this->assertFalse(isIndexed([10 => 'a', 11 => 'b'])); // Ne commence pas à 0
    }

    public function testReturnsFalseForStringNumericKeys(): void
    {
        $this->assertFalse(isIndexed(['2' => 'c']));
    }

    public function testReturnsFalseForStringKeys(): void
    {
        $this->assertFalse(isIndexed(['name' => 'Alice', 'age' => 30]));
        $this->assertFalse(isIndexed(['a' => 1, 'b' => 2]));
    }

    public function testReturnsFalseForMixedKeys(): void
    {
        $this->assertFalse(isIndexed([0 => 'a', 'name' => 'Bob']));
        $this->assertFalse(isIndexed(['a', 'b', 'key' => 'value']));
    }

    public function testReturnsTrueForSingleIndexedElement(): void
    {
        $this->assertTrue(isIndexed(['single']));
        $this->assertTrue(isIndexed([0 => 'single']));
        $this->assertTrue(isIndexed(['0' => 'single'])); // Clé string
    }

    public function testReturnsFalseForSingleNonIndexedElement(): void
    {
        $this->assertFalse(isIndexed([1 => 'single']));
        $this->assertFalse(isIndexed(['key' => 'single']));
    }
}
