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

    public function testCleanReindexNumericArray(): void
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

    public function testCleanWithFalsyFlagRemovesAllFalsyValues(): void
    {
        $input    = ['foo', '', null, false, 0, '0', 'bar'];
        $expected = ['foo', 'bar'];

        $this->assertSame($expected, clean($input, CleanFlag::FALSY));
    }

    public function testCleanTrimsWhitespaceStringsWhenTrimFlagIsSet(): void
    {
        $input    = ['a' => '   ', 'b' => "\n\t", 'c' => ' ok '];
        $expected = ['c' => ' ok '];

        $this->assertSame($expected, clean($input, CleanFlag::TRIM | CleanFlag::EMPTY ) );
    }

    public function testCleanRecursiveRemovesNestedEmptyStrings(): void
    {
        $input    = [
            'users' => [
                ['name' => '', 'email' => 'bob@example.com'],
                ['name' => 'Alice', 'email' => '']
            ]
        ];
        $expected = [
            'users' => [
                ['email' => 'bob@example.com'],
                ['name' => 'Alice']
            ]
        ];

        $this->assertSame($expected, clean($input, CleanFlag::RECURSIVE | CleanFlag::EMPTY));
    }

    public function testCleanRecursiveRemovesEmptyArrays(): void
    {
        $input    = [
            'group1' => [],
            'group2' => [['name' => 'Alice'], []]
        ];
        $expected = [
            'group2' => [['name' => 'Alice']]
        ];

        $this->assertSame($expected, clean($input, CleanFlag::RECURSIVE | CleanFlag::EMPTY_ARR));
    }

    public function testCleanWithMainFlagBehavesLikeDefault(): void
    {
        $input    = ['foo', '', null, '   ', [], 'bar'];
        $expected = ['foo', 'bar'];

        $this->assertSame($expected, clean($input, CleanFlag::MAIN));
    }

    public function testCleanWithDefaultFlagEquivalentToMain(): void
    {
        $input    = ['foo', '', null, '   ', [], 'bar'];
        $expected = ['foo', 'bar'];

        $this->assertSame(clean($input, CleanFlag::DEFAULT), clean($input, CleanFlag::MAIN));
    }

    public function testCleanCombinesMultipleFlagsCorrectly(): void
    {
        $input    = ['foo', '', null, '   ', false, 'bar', 0];
        $expected = ['foo', false , 'bar' , 0];

        $flags = CleanFlag::NULLS | CleanFlag::EMPTY | CleanFlag::TRIM;
        $this->assertSame($expected, clean($input, $flags));
    }
}
