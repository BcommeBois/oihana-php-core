<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\groupBy;

use PHPUnit\Framework\TestCase;

class GroupByTest extends TestCase
{
    public function testGroupsByComputedKeyPreservingOriginalKeys() : void
    {
        $people =
        [
            [ 'name' => 'Alice' , 'city' => 'Paris' ] ,
            [ 'name' => 'Bob'   , 'city' => 'Lyon'  ] ,
            [ 'name' => 'Carol' , 'city' => 'Paris' ] ,
        ];

        $expected =
        [
            'Paris' => [ 0 => $people[ 0 ] , 2 => $people[ 2 ] ] ,
            'Lyon'  => [ 1 => $people[ 1 ] ] ,
        ];

        $this->assertSame( $expected , groupBy( $people , fn( $p ) => $p[ 'city' ] ) ) ;
    }

    public function testKeyerReceivesValueAndKey() : void
    {
        $items = [ 'a' => 1 , 'b' => 2 , 'c' => 3 ] ;
        $result = groupBy( $items , fn( $value , $key ) => $key ) ;
        $this->assertSame( [ 'a' => [ 'a' => 1 ] , 'b' => [ 'b' => 2 ] , 'c' => [ 'c' => 3 ] ] , $result ) ;
    }

    public function testCastsNonScalarKeyToString() : void
    {
        $result = groupBy( [ 1 , 2 ] , fn( $n ) => null ) ;
        $this->assertSame( [ '' => [ 0 => 1 , 1 => 2 ] ] , $result ) ;
    }

    public function testEmptyArray() : void
    {
        $this->assertSame( [] , groupBy( [] , fn( $v ) => $v ) ) ;
    }
}
