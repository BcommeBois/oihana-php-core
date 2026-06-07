<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\partition;

use PHPUnit\Framework\TestCase;

class PartitionTest extends TestCase
{
    public function testSplitsPreservingKeys() : void
    {
        [ $even , $odd ] = partition( [ 1 , 2 , 3 , 4 ] , fn( $n ) => $n % 2 === 0 ) ;
        $this->assertSame( [ 1 => 2 , 3 => 4 ] , $even ) ;
        $this->assertSame( [ 0 => 1 , 2 => 3 ] , $odd ) ;
    }

    public function testPredicateReceivesValueAndKey() : void
    {
        $items = [ 'a' => 1 , 'b' => 2 , 'c' => 3 ] ;
        [ $pass , $fail ] = partition( $items , fn( $value , $key ) => $key !== 'b' ) ;
        $this->assertSame( [ 'a' => 1 , 'c' => 3 ] , $pass ) ;
        $this->assertSame( [ 'b' => 2 ] , $fail ) ;
    }

    public function testAllPass() : void
    {
        [ $pass , $fail ] = partition( [ 1 , 2 ] , fn( $n ) => true ) ;
        $this->assertSame( [ 0 => 1 , 1 => 2 ] , $pass ) ;
        $this->assertSame( [] , $fail ) ;
    }

    public function testEmptyArray() : void
    {
        $this->assertSame( [ [] , [] ] , partition( [] , fn( $v ) => true ) ) ;
    }
}
