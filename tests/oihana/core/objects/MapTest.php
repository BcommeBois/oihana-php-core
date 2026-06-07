<?php

namespace tests\oihana\core\objects;

use function oihana\core\objects\map;

use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    public function testTransformsValues() : void
    {
        $prices = (object) [ 'a' => 10 , 'b' => 20 ] ;
        $this->assertEquals( (object) [ 'a' => 20 , 'b' => 40 ] , map( $prices , fn( $v ) => $v * 2 ) ) ;
    }

    public function testCallbackReceivesValueAndKey() : void
    {
        $object = (object) [ 'x' => 1 , 'y' => 2 ] ;
        $this->assertEquals( (object) [ 'x' => 'x=1' , 'y' => 'y=2' ] , map( $object , fn( $v , $k ) => "$k=$v" ) ) ;
    }

    public function testDoesNotMutateSource() : void
    {
        $object = (object) [ 'a' => 1 ] ;
        map( $object , fn( $v ) => $v + 100 ) ;
        $this->assertEquals( (object) [ 'a' => 1 ] , $object ) ;
    }

    public function testEmptyObject() : void
    {
        $this->assertEquals( (object) [] , map( (object) [] , fn( $v ) => $v ) ) ;
    }
}
