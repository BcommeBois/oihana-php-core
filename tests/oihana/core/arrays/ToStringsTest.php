<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\toStrings;

use PHPUnit\Framework\TestCase;

class ToStringsTest extends TestCase
{
    public function testKeepsStringsAndCastsInts() : void
    {
        $this->assertSame( [ 'a' , '1' , 'b' ] , toStrings( [ 'a' , 1 , 'b' ] ) ) ;
    }

    public function testDropsNonScalarStringableItems() : void
    {
        $this->assertSame( [ 'a' , 'b' ] , toStrings( [ 'a' , 2.5 , null , false , [ 'x' ] , 'b' ] ) ) ;
    }

    public function testWrapsBareScalar() : void
    {
        $this->assertSame( [ 'solo' ] , toStrings( 'solo' ) ) ;
        $this->assertSame( [ '7' ] , toStrings( 7 ) ) ;
    }

    public function testDropsBareFloat() : void
    {
        $this->assertSame( [] , toStrings( 2.5 ) ) ;
    }

    public function testNullYieldsEmptyArray() : void
    {
        $this->assertSame( [] , toStrings( null ) ) ;
    }

    public function testEmptyArrayYieldsEmptyArray() : void
    {
        $this->assertSame( [] , toStrings( [] ) ) ;
    }

    public function testResultIsReindexedFromZero() : void
    {
        $this->assertSame( [ '1' , '2' ] , toStrings( [ 'a' => 1 , 'b' => 2.5 , 'c' => 2 ] ) ) ;
    }
}
