<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\delta;

use PHPUnit\Framework\TestCase;

class DeltaTest extends TestCase
{
    public function testReportsRemovedAddedAndKept() : void
    {
        [ $removed , $added , $kept ] = delta( [ 'a' , 'b' , 'c' ] , [ 'b' , 'c' , 'd' ] ) ;

        $this->assertSame( [ 'a' ] , $removed ) ;
        $this->assertSame( [ 'd' ] , $added ) ;
        $this->assertSame( [ 'b' , 'c' ] , $kept ) ;
    }

    public function testIdenticalListsHaveNoRemovedOrAdded() : void
    {
        [ $removed , $added , $kept ] = delta( [ 'a' , 'b' ] , [ 'a' , 'b' ] ) ;

        $this->assertSame( [] , $removed ) ;
        $this->assertSame( [] , $added ) ;
        $this->assertSame( [ 'a' , 'b' ] , $kept ) ;
    }

    public function testDisjointListsHaveNothingKept() : void
    {
        [ $removed , $added , $kept ] = delta( [ 'a' , 'b' ] , [ 'c' , 'd' ] ) ;

        $this->assertSame( [ 'a' , 'b' ] , $removed ) ;
        $this->assertSame( [ 'c' , 'd' ] , $added ) ;
        $this->assertSame( [] , $kept ) ;
    }

    public function testEmptyBefore() : void
    {
        [ $removed , $added , $kept ] = delta( [] , [ 'a' , 'b' ] ) ;

        $this->assertSame( [] , $removed ) ;
        $this->assertSame( [ 'a' , 'b' ] , $added ) ;
        $this->assertSame( [] , $kept ) ;
    }

    public function testEmptyNow() : void
    {
        [ $removed , $added , $kept ] = delta( [ 'a' , 'b' ] , [] ) ;

        $this->assertSame( [ 'a' , 'b' ] , $removed ) ;
        $this->assertSame( [] , $added ) ;
        $this->assertSame( [] , $kept ) ;
    }

    public function testResultsAreReindexedFromZero() : void
    {
        [ $removed , $added , $kept ] = delta( [ 'x' => 'a' , 'y' => 'b' ] , [ 'z' => 'b' ] ) ;

        $this->assertSame( [ 'a' ] , $removed ) ;
        $this->assertSame( [] , $added ) ;
        $this->assertSame( [ 'b' ] , $kept ) ;
    }
}
