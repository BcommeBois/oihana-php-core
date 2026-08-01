<?php

namespace tests\oihana\core\arrays;

use InvalidArgumentException;

use oihana\core\arrays\CleanFlag;
use function oihana\core\arrays\clean;

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

    public function testCleanDropsWhitespaceOnlyStringsWhenTrimIsCombinedWithEmpty(): void
    {
        $input    = ['a' => '   ', 'b' => "\n\t", 'c' => ' ok '];
        $expected = ['c' => ' ok ']; // the kept value is never trimmed

        $this->assertSame($expected, clean($input, CleanFlag::TRIM | CleanFlag::EMPTY ) );
    }

    public function testCleanTrimFlagAloneHasNoEffect(): void
    {
        $input = ['a' => '   ', 'b' => '', 'c' => null, 'd' => 'ok'];

        $this->assertSame($input, clean($input, CleanFlag::TRIM));
    }

    public function testCleanEmptyArrayReturnsNullWhenReturnNullIsSet(): void
    {
        $this->assertNull(clean([], CleanFlag::NORMALIZE));
        $this->assertNull(clean([], CleanFlag::NULLS | CleanFlag::RETURN_NULL));
        $this->assertNull(clean([], CleanFlag::RETURN_NULL));
    }

    public function testCleanTreatsAnAlreadyEmptyInputLikeAnEmptiedOne(): void
    {
        $flags = CleanFlag::DEFAULT | CleanFlag::RETURN_NULL;

        $this->assertSame(clean([], $flags), clean(['a' => null], $flags));
        $this->assertSame(clean([], $flags), clean(['a' => '   '], $flags));
    }

    public function testCleanEmptyArrayStillValidatesFlagsFirst(): void
    {
        $this->expectException(InvalidArgumentException::class);
        clean([], 1 << 20);
    }

    public function testCleanReturnNullIsNotPropagatedToNestedArrays(): void
    {
        // Without EMPTY_ARR nothing intercepts a nested array that cleans to empty : it must
        // stay [] and never become a null that the NULLS flag can no longer catch.
        $flags = CleanFlag::NULLS | CleanFlag::RECURSIVE | CleanFlag::RETURN_NULL;

        $this->assertSame(['a' => [], 'b' => 2], clean(['a' => ['x' => null], 'b' => 2], $flags));
        $this->assertSame(['a' => ['b' => []], 'z' => 1], clean(['a' => ['b' => ['c' => null]], 'z' => 1], $flags));
    }

    public function testCleanReturnNullTreatsAlreadyEmptyAndEmptiedNestedArraysAlike(): void
    {
        $flags = CleanFlag::NULLS | CleanFlag::RECURSIVE | CleanFlag::RETURN_NULL;

        $this->assertSame(clean(['a' => [], 'b' => 2], $flags), clean(['a' => ['x' => null], 'b' => 2], $flags));
    }

    public function testCleanReturnNullStillAppliesToTheOutermostCall(): void
    {
        $flags = CleanFlag::NULLS | CleanFlag::RECURSIVE | CleanFlag::RETURN_NULL;

        $this->assertNull(clean(['x' => null], $flags));
        $this->assertNull(clean(['a' => ['x' => null]], $flags | CleanFlag::EMPTY_ARR));
    }

    public function testCleanReturnNullWithEmptyArrIsUnchanged(): void
    {
        $input = ['a' => ['x' => null], 'b' => 2];

        $this->assertSame(['b' => 2], clean($input, CleanFlag::NORMALIZE));
        $this->assertSame(['b' => 2], clean($input, CleanFlag::DEFAULT));
    }

    public function testCleanFalsyFlagNeverAppliesToArrays(): void
    {
        $input = [0, '', null, false, 'ok', [], '0'];

        $this->assertSame(['ok', []], clean($input, CleanFlag::FALSY));
        $this->assertSame(['ok'],     clean($input, CleanFlag::FALSY | CleanFlag::EMPTY_ARR));
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
