<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class UniqueTest extends TestCase
{
    public function testRemoveDuplicatesDefaultSort(): void
    {
        $input = ["a", "b", "a", "c", "b"];
        $expected = ["a", "b", "c"];
        $this->assertSame ($expected , unique( $input ) ) ;
    }

    public function testRemoveDuplicatesNumericSort(): void
    {
        $input = [1, "1", 2, "2", 3];
        $expected = [1, 2, 3];
        $this->assertSame($expected, unique( $input , SORT_NUMERIC ) );
    }

    public function testRemoveDuplicatesStringSort(): void
    {
        $input = [1, "1", 2, "2", 3];
        $expected = [ 1 , 2 , 3 ] ;
        $this->assertSame( $expected , unique( $input, SORT_STRING ) );
    }

    public function testEmptyArray(): void
    {
        $this->assertSame([], unique([]));
    }

    public function testPreservesOrder(): void
    {
        $input = ["apple", "banana", "apple", "cherry"];
        $expected = ["apple", "banana", "cherry"];
        $this->assertSame($expected, unique( $input ) );
    }
}
