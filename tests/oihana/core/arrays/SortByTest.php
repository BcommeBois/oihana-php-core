<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\sortBy;

use PHPUnit\Framework\TestCase;

class SortByTest extends TestCase
{
    public function testSortsAscendingByComputedValuePreservingKeys() : void
    {
        $people =
        [
            [ 'name' => 'Alice' , 'age' => 30 ] ,
            [ 'name' => 'Bob'   , 'age' => 25 ] ,
            [ 'name' => 'Carol' , 'age' => 35 ] ,
        ];

        $expected =
        [
            1 => [ 'name' => 'Bob'   , 'age' => 25 ] ,
            0 => [ 'name' => 'Alice' , 'age' => 30 ] ,
            2 => [ 'name' => 'Carol' , 'age' => 35 ] ,
        ];

        $this->assertSame( $expected , sortBy( $people , fn( $p ) => $p[ 'age' ] ) ) ;
    }

    public function testSortsDescending() : void
    {
        $result = sortBy( [ 'a' => 3 , 'b' => 1 , 'c' => 2 ] , fn( $v ) => $v , true ) ;
        $this->assertSame( [ 'a' => 3 , 'c' => 2 , 'b' => 1 ] , $result ) ;
    }

    public function testIsStableForEqualWeights() : void
    {
        $items =
        [
            [ 'id' => 1 , 'group' => 'x' ] ,
            [ 'id' => 2 , 'group' => 'x' ] ,
            [ 'id' => 3 , 'group' => 'x' ] ,
        ];
        // All weights equal: original relative order must be preserved.
        $this->assertSame( $items , sortBy( $items , fn( $p ) => $p[ 'group' ] ) ) ;
    }

    public function testSelectorReceivesValueAndKey() : void
    {
        $result = sortBy( [ 'b' => 0 , 'a' => 0 , 'c' => 0 ] , fn( $value , $key ) => $key ) ;
        $this->assertSame( [ 'a' => 0 , 'b' => 0 , 'c' => 0 ] , $result ) ;
    }

    public function testEmptyArray() : void
    {
        $this->assertSame( [] , sortBy( [] , fn( $v ) => $v ) ) ;
    }
}
