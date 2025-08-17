<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class CleanTest extends TestCase
{
    public function testCleanRemovesNullsAndEmptyStringsFromIndexedArray(): void
    {
        $input    = ['foo', '', null, 'bar', '0', 0, false];
        $expected = ['foo', 'bar', '0', 0, false];

        $this->assertSame($expected, clean($input));
    }

    public function testCleanReindexesNumericArray(): void
    {
        $input    = [0 => 'a', 1 => '', 2 => null, 3 => 'b'];
        $expected = ['a', 'b'];

        $this->assertSame($expected, clean($input));
    }

    public function testCleanPreservesKeysForAssociativeArray(): void
    {
        $input    = ['id' => 1, 'name' => '', 'email' => null];
        $expected = ['id' => 1];

        $this->assertSame($expected, clean($input));
    }

    public function testCleanRemovesEmptyArrays(): void
    {
        $input    = ['foo', [], 'bar', ['nested' => []], ['a' => 1]];
        $expected = ['foo', 'bar', ['a' => 1]];

        $this->assertSame($expected, clean($input));
    }

    public function testCleanEmptyArrayReturnsEmptyArray(): void
    {
        $this->assertSame([], clean([]));
    }

    public function testCleanArrayWithoutNullOrEmptyValuesRemainsUnchanged(): void
    {
        $input    = ['x' => 1, 'y' => 'hello', 'z' => [1, 2]];
        $expected = $input;

        $this->assertSame($expected, clean($input));
    }

    public function testCleanMixedTypesKeepsFalsyButNonEmptyValues(): void
    {
        $input    = ['a' => 0, 'b' => false, 'c' => '', 'd' => null];
        $expected = ['a' => 0, 'b' => false];

        $this->assertSame($expected, clean($input));
    }
}
