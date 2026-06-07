<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\toArray;

use PHPUnit\Framework\TestCase;

class ToArrayTest extends TestCase
{
    public function testWrapsScalarInArray() : void
    {
        $this->assertSame( [ 123 ] , toArray( 123 ) ) ;
        $this->assertSame( [ 'hello' ] , toArray( 'hello' ) ) ;
    }

    public function testWrapsNull() : void
    {
        $this->assertSame( [ null ] , toArray( null ) ) ;
    }

    public function testReturnsArrayUnchanged() : void
    {
        $this->assertSame( [ 1 , 2 , 3 ] , toArray( [ 1 , 2 , 3 ] ) ) ;
    }

    public function testPreservesAssociativeArray() : void
    {
        $assoc = [ 'a' => 1 , 'b' => 2 ] ;
        $this->assertSame( $assoc , toArray( $assoc ) ) ;
    }

    public function testWrapsObject() : void
    {
        $obj = new \stdClass() ;
        $this->assertSame( [ $obj ] , toArray( $obj ) ) ;
    }
}
