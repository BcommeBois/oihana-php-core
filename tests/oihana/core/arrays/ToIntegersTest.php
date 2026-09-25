<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\toIntegers;

use PHPUnit\Framework\TestCase;

class ToIntegersTest extends TestCase
{
    public function testKeepsIntegers() : void
    {
        $this->assertSame( [ 12 , -3 , 0 ] , toIntegers( [ 12 , -3 , 0 ] ) ) ;
    }

    public function testCastsStringsThatWriteAnInteger() : void
    {
        $this->assertSame( [ 34 , 56 , -4 , 5 , 7 ] , toIntegers( [ '34' , ' 56 ' , '-4' , '+5' , '007' ] ) ) ;
    }

    public function testDropsFloatsEvenWhole() : void
    {
        $this->assertSame( [ 1 ] , toIntegers( [ 7.5 , 350.0 , 1 ] ) ) ;
    }

    public function testDropsStringsThatWriteSomethingElse() : void
    {
        $this->assertSame( [] , toIntegers( [ '8.5' , '1e3' , '0x1A' , '12a' , '' , ' ' , '+' ] ) ) ;
    }

    public function testDropsBooleansNullArraysAndObjects() : void
    {
        $this->assertSame( [ 2 ] , toIntegers( [ true , false , null , [ 1 ] , new \stdClass() , 2 ] ) ) ;
    }

    public function testKeepsOrderAndDuplicates() : void
    {
        $this->assertSame( [ 3 , 1 , 3 ] , toIntegers( [ 3 , '1' , 3 ] ) ) ;
    }

    public function testWrapsBareScalar() : void
    {
        $this->assertSame( [ 42 ] , toIntegers( 42 ) ) ;
        $this->assertSame( [ 42 ] , toIntegers( '42' ) ) ;
    }

    public function testDropsBareFloat() : void
    {
        $this->assertSame( [] , toIntegers( 2.5 ) ) ;
    }

    public function testNullYieldsEmptyArray() : void
    {
        $this->assertSame( [] , toIntegers( null ) ) ;
    }

    public function testEmptyArrayYieldsEmptyArray() : void
    {
        $this->assertSame( [] , toIntegers( [] ) ) ;
    }

    public function testResultIsReindexedFromZero() : void
    {
        $this->assertSame( [ 1 , 2 ] , toIntegers( [ 'a' => 1 , 'b' => 2.5 , 'c' => '2' ] ) ) ;
    }
}
