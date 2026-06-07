<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
{
    public function testSetsSingleKey() : void
    {
        $data = [] ;
        set( $data , 'key' , 'value' ) ;
        $this->assertSame( [ 'key' => 'value' ] , $data ) ;
    }

    public function testSetsNestedValueByDottedKey() : void
    {
        $data = [] ;
        set( $data , 'user.name' , 'Alice' ) ;
        $this->assertSame( [ 'user' => [ 'name' => 'Alice' ] ] , $data ) ;
    }

    public function testExtendsExistingStructure() : void
    {
        $data = [ 'user' => [ 'name' => 'Marc' ] ] ;
        set( $data , 'user.address.country' , 'France' ) ;
        $this->assertSame( [ 'user' => [ 'name' => 'Marc' , 'address' => [ 'country' => 'France' ] ] ] , $data ) ;
    }

    public function testNullKeyReplacesEntireArray() : void
    {
        $data   = [ 'a' => 1 ] ;
        $result = set( $data , null , [ 'id' => 1 ] ) ;
        $this->assertSame( [ 'id' => 1 ] , $data ) ;
        $this->assertSame( [ 'id' => 1 ] , $result ) ;
    }

    public function testOverwritesNonArrayIntermediate() : void
    {
        $data = [ 'a' => 'scalar' ] ;
        set( $data , 'a.b' , 'value' ) ;
        $this->assertSame( [ 'a' => [ 'b' => 'value' ] ] , $data ) ;
    }

    public function testCustomSeparator() : void
    {
        $data = [] ;
        set( $data , 'a/b/c' , 'value' , '/' ) ;
        $this->assertSame( [ 'a' => [ 'b' => [ 'c' => 'value' ] ] ] , $data ) ;
    }

    public function testReturnsDeepestSetNode() : void
    {
        // set() returns the innermost array node reached by the key path.
        $data   = [ 'user' => [ 'name' => 'Marc' ] ] ;
        $return = set( $data , 'user.address.country' , 'France' ) ;
        $this->assertSame( [ 'country' => 'France' ] , $return ) ;
    }

    public function testCopyModeSetsTheNestedValue() : void
    {
        $data   = [ 'user' => [ 'name' => 'Marc' ] ] ;
        $return = set( $data , 'user.address.country' , 'France' , '.' , true ) ;
        $this->assertSame( [ 'country' => 'France' ] , $return ) ;
        $this->assertSame( 'France' , $data['user']['address']['country'] ) ;
        $this->assertSame( 'Marc' , $data['user']['name'] ) ;
    }
}
