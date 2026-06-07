<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\keyBy;

use PHPUnit\Framework\TestCase;

class KeyByTest extends TestCase
{
    public function testIndexesByComputedKey() : void
    {
        $users =
        [
            [ 'id' => 10 , 'name' => 'Alice' ] ,
            [ 'id' => 20 , 'name' => 'Bob'   ] ,
        ];

        $expected =
        [
            10 => [ 'id' => 10 , 'name' => 'Alice' ] ,
            20 => [ 'id' => 20 , 'name' => 'Bob'   ] ,
        ];

        $this->assertSame( $expected , keyBy( $users , fn( $u ) => $u[ 'id' ] ) ) ;
    }

    public function testLastWinsOnCollision() : void
    {
        $rows =
        [
            [ 'k' => 'x' , 'v' => 1 ] ,
            [ 'k' => 'x' , 'v' => 2 ] ,
        ];
        $this->assertSame( [ 'x' => [ 'k' => 'x' , 'v' => 2 ] ] , keyBy( $rows , fn( $r ) => $r[ 'k' ] ) ) ;
    }

    public function testKeyerReceivesValueAndKey() : void
    {
        $items = [ 'a' => 1 , 'b' => 2 ] ;
        $this->assertSame( [ 'A' => 1 , 'B' => 2 ] , keyBy( $items , fn( $value , $key ) => strtoupper( $key ) ) ) ;
    }

    public function testCastsNonScalarKeyToString() : void
    {
        $this->assertSame( [ '1' => 'b' ] , keyBy( [ 'a' , 'b' ] , fn( $v , $k ) => true ) ) ;
    }

    public function testEmptyArray() : void
    {
        $this->assertSame( [] , keyBy( [] , fn( $v ) => $v ) ) ;
    }
}
