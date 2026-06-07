<?php

namespace tests\oihana\core\objects;

use stdClass;

use function oihana\core\objects\omit;

use PHPUnit\Framework\TestCase;

class OmitTest extends TestCase
{
    public function testRemovesListedProperties() : void
    {
        $user = (object) [ 'id' => 42 , 'name' => 'Alice' , 'password' => 'secret' ] ;
        $this->assertEquals( (object) [ 'id' => 42 , 'name' => 'Alice' ] , omit( $user , [ 'password' ] ) ) ;
    }

    public function testIgnoresMissingKeys() : void
    {
        $user = (object) [ 'id' => 42 ] ;
        $this->assertEquals( (object) [ 'id' => 42 ] , omit( $user , [ 'unknown' ] ) ) ;
    }

    public function testDoesNotMutateSource() : void
    {
        $user = (object) [ 'id' => 42 , 'name' => 'Alice' ] ;
        omit( $user , [ 'name' ] ) ;
        $this->assertEquals( (object) [ 'id' => 42 , 'name' => 'Alice' ] , $user ) ;
    }

    public function testOmitAllReturnsEmptyObject() : void
    {
        $this->assertEquals( new stdClass() , omit( (object) [ 'a' => 1 , 'b' => 2 ] , [ 'a' , 'b' ] ) ) ;
    }
}
