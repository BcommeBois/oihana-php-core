<?php

namespace tests\oihana\core\objects;

use stdClass;

use function oihana\core\objects\filter;

use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testKeepsMatchingProperties() : void
    {
        $values = (object) [ 'a' => 1 , 'b' => 2 , 'c' => 3 ] ;
        $this->assertEquals( (object) [ 'a' => 1 , 'c' => 3 ] , filter( $values , fn( $v ) => $v % 2 === 1 ) ) ;
    }

    public function testCallbackReceivesValueAndKey() : void
    {
        $object = (object) [ 'keep' => 1 , 'drop' => 2 ] ;
        $this->assertEquals( (object) [ 'keep' => 1 ] , filter( $object , fn( $v , $k ) => $k === 'keep' ) ) ;
    }

    public function testDoesNotMutateSource() : void
    {
        $object = (object) [ 'a' => 1 , 'b' => 2 ] ;
        filter( $object , fn( $v ) => $v > 1 ) ;
        $this->assertEquals( (object) [ 'a' => 1 , 'b' => 2 ] , $object ) ;
    }

    public function testNoMatchReturnsEmptyObject() : void
    {
        $this->assertEquals( new stdClass() , filter( (object) [ 'a' => 1 ] , fn( $v ) => false ) ) ;
    }
}
