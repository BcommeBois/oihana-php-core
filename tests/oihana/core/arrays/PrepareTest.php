<?php

namespace oihana\core\arrays ;

use oihana\core\options\ArrayOption;
use PHPUnit\Framework\TestCase;

final class PrepareTest extends TestCase
{
    public function testPrepareWithoutOptionsReturnsSameArray(): void
    {
        $input = ['a' => 1, 'b' => 2];
        $this->assertSame($input, prepare($input));
    }

    public function testPrepareWithReduceTrueRemovesNulls(): void
    {
        $input = ['a' => 1, 'b' => null, 'c' => 0];
        $expected = ['a' => 1, 'c' => 0];

        $output = prepare( $input ,
        [
            ArrayOption::REDUCE => true
        ]);

        $this->assertSame($expected, $output);
    }

    public function testPrepareWithCustomReduceCallable(): void
    {
        $input = ['a' => 1, 'b' => 2, 'c' => 3];
        $expected = ['a' => 1, 'c' => 3];

        $output = prepare( $input ,
        [
            ArrayOption::REDUCE => fn( $k, $v) => $v !== 2
        ]);

        $this->assertSame($expected, $output);
    }

    public function testPrepareWithBeforePrependsKeys(): void
    {
        $input = ['a' => 1];
        $expected = ['meta' => 0, 'a' => 1];

        $output = prepare( $input ,
        [
            ArrayOption::BEFORE => ['meta' => 0],
            ArrayOption::SORT   => false ,
        ]);

        $this->assertSame($expected, $output);
    }

    public function testPrepareWithAfterAppendsKeys(): void
    {
        $input    = [ 'a' => 1 ] ;
        $expected = [ 'a' => 1 , 'meta' => 0] ;

        $output = prepare( $input ,
        [
            ArrayOption::AFTER => ['meta' => 0]
        ]);

        $this->assertSame($expected, $output);
    }

    public function testPrepareWithFirstKeysReorders(): void
    {
        $input = ['a' => 1, 'b' => 2, 'c' => 3];
        $expected = ['c' => 3, 'a' => 1, 'b' => 2];

        $output = prepare( $input ,
        [
            ArrayOption::FIRST_KEYS => ['c']
        ]);

        $this->assertSame($expected, $output);
    }

    public function testPrepareWithSortSortsKeysAlphabetically(): void
    {
        $input = ['b' => 2, 'a' => 1, 'c' => 3];
        $expected = ['a' => 1, 'b' => 2, 'c' => 3];

        $output = prepare( $input ,
        [
            ArrayOption::SORT => true
        ]);

        $this->assertSame($expected, $output);
    }

    public function testPrepareCombinesAllOptions(): void
    {
        $input = ['b' => null, 'a' => 1, 'c' => 3];
        $expected = ['meta' => 0, 'a' => 1, 'c' => 3, 'footer' => 'end'];

        $output = prepare( $input,
        [
            ArrayOption::REDUCE      => true,
            ArrayOption::BEFORE      => ['meta' => 0],
            ArrayOption::AFTER       => ['footer' => 'end'],
            ArrayOption::FIRST_KEYS  => ['meta'],
            ArrayOption::SORT        => true,
        ]);

        $this->assertSame($expected, $output);
    }
}
