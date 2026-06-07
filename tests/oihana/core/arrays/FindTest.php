<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\find;

use PHPUnit\Framework\TestCase;

class FindTest extends TestCase
{
    public function testReturnsFirstMatch() : void
    {
        $this->assertSame( 4 , find( [ 1 , 3 , 4 , 6 ] , fn( $n ) => $n % 2 === 0 ) ) ;
    }

    public function testReturnsDefaultWhenNoMatch() : void
    {
        $this->assertNull( find( [ 1 , 3 ] , fn( $n ) => $n > 10 ) ) ;
        $this->assertSame( -1 , find( [ 1 , 3 ] , fn( $n ) => $n > 10 , -1 ) ) ;
    }

    public function testPredicateReceivesValueAndKey() : void
    {
        $items = [ 'a' => 1 , 'b' => 2 , 'c' => 3 ] ;
        $this->assertSame( 2 , find( $items , fn( $value , $key ) => $key === 'b' ) ) ;
    }

    public function testReturnsFirstWhenSeveralMatch() : void
    {
        $this->assertSame( 3 , find( [ 1 , 3 , 5 ] , fn( $n ) => $n >= 3 ) ) ;
    }

    public function testEmptyArrayReturnsDefault() : void
    {
        $this->assertSame( 'none' , find( [] , fn( $v ) => true , 'none' ) ) ;
    }
}
