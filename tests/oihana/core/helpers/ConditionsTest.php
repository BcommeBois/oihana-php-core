<?php

namespace tests\oihana\core\helpers;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function oihana\core\helpers\conditions;

class ConditionsTest extends TestCase
{
    public function testNullReturnsIsNullPredicate() : void
    {
        $result = conditions() ;
        $this->assertCount( 1 , $result ) ;
        $this->assertTrue( $result[0]( null ) ) ;
        $this->assertFalse( $result[0]( 'x' ) ) ;
    }

    public function testCallableIsWrappedInArray() : void
    {
        $fn = fn( $v ) => is_numeric( $v ) ;
        $this->assertSame( [ $fn ] , conditions( $fn ) ) ;
    }

    public function testStringCallableIsWrappedInArray() : void
    {
        $this->assertSame( [ 'strlen' ] , conditions( 'strlen' ) ) ;
    }

    public function testArrayKeepsOnlyCallablesWhenNotThrowable() : void
    {
        $fn     = fn( $v ) => $v === null ;
        $result = conditions( [ $fn , 'this_is_not_a_function_xyz' ] , false ) ;
        $this->assertCount( 1 , $result ) ;
        $this->assertSame( $fn , array_values( $result )[0] ) ;
    }

    public function testArrayThrowsOnNonCallableWhenThrowable() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        conditions( [ 'this_is_not_a_function_xyz' ] , true ) ;
    }

    public function testNonCallableStringReturnsEmptyWhenNotThrowable() : void
    {
        $this->assertSame( [] , conditions( 'this_is_not_a_function_xyz' , false ) ) ;
    }

    public function testNonCallableStringThrowsWhenThrowable() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        conditions( 'this_is_not_a_function_xyz' , true ) ;
    }
}
