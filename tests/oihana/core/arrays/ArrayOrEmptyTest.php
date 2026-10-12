<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\arrayOrEmpty;
use function oihana\core\arrays\toArray;

use PHPUnit\Framework\TestCase;

use stdClass;

class ArrayOrEmptyTest extends TestCase
{
    public function testReturnsAnIndexedArrayUnchanged() : void
    {
        $this->assertSame( [ 1 , 2 , 3 ] , arrayOrEmpty( [ 1 , 2 , 3 ] ) ) ;
    }

    public function testReturnsAnAssociativeArrayUnchanged() : void
    {
        // assertSame, so the keys and their order are asserted too.
        $assoc = [ 'name' => 'Nemo' , 'age' => 7 ] ;

        $this->assertSame( $assoc , arrayOrEmpty( $assoc ) ) ;
    }

    public function testReturnsAnEmptyArrayUnchanged() : void
    {
        // An empty array IS an array : it is answered as itself, not replaced.
        $this->assertSame( [] , arrayOrEmpty( [] ) ) ;
    }

    public function testKeepsANestedArrayWhole() : void
    {
        $nested = [ 'crew' => [ [ 'name' => 'Nemo' ] , [ 'name' => 'Conseil' ] ] ] ;

        $this->assertSame( $nested , arrayOrEmpty( $nested ) ) ;
    }

    public function testDropsNull() : void
    {
        $this->assertSame( [] , arrayOrEmpty( null ) ) ;
    }

    public function testDropsAString() : void
    {
        $this->assertSame( [] , arrayOrEmpty( 'not a record' ) ) ;
        $this->assertSame( [] , arrayOrEmpty( '' ) ) ;
    }

    public function testDropsANumber() : void
    {
        $this->assertSame( [] , arrayOrEmpty( 42 ) ) ;
        $this->assertSame( [] , arrayOrEmpty( 0 ) ) ;
        $this->assertSame( [] , arrayOrEmpty( 3.14 ) ) ;
    }

    public function testDropsABoolean() : void
    {
        $this->assertSame( [] , arrayOrEmpty( true ) ) ;
        $this->assertSame( [] , arrayOrEmpty( false ) ) ;
    }

    public function testDropsAnObject() : void
    {
        // An object reads like a record but is not one : a caller asking for an
        // array gets nothing rather than a value its reads would mishandle.
        $this->assertSame( [] , arrayOrEmpty( new stdClass() ) ) ;
    }

    public function testDropsAClosure() : void
    {
        $this->assertSame( [] , arrayOrEmpty( static fn() :int => 1 ) ) ;
    }

    public function testReadsAFieldThatMayNotBeThere() : void
    {
        // The shape the function exists for : a field of an unknown payload,
        // named once instead of twice.
        $document = [ 'tags' => [ 'nautilus' , 'abyss' ] , 'title' => 'a string' ] ;

        $this->assertSame( [ 'nautilus' , 'abyss' ] , arrayOrEmpty( $document[ 'tags'    ] ?? null ) ) ;
        $this->assertSame( []                       , arrayOrEmpty( $document[ 'title'   ] ?? null ) ) ;
        $this->assertSame( []                       , arrayOrEmpty( $document[ 'absent'  ] ?? null ) ) ;
    }

    public function testDoesTheOppositeOfToArrayOnTheSameValue() : void
    {
        // 🔑 The two functions read almost the same at a call site and behave in
        // opposite ways. The contrast is asserted here so it is GUARDED, and not
        // only written in the docblocks : whichever of the two ever changes its
        // mind, this test says so.
        $this->assertSame( []           , arrayOrEmpty( null ) ) ;
        $this->assertSame( [ null ]     , toArray( null ) ) ;

        $this->assertSame( []           , arrayOrEmpty( 42 ) ) ;
        $this->assertSame( [ 42 ]       , toArray( 42 ) ) ;

        $this->assertSame( []           , arrayOrEmpty( 'squid' ) ) ;
        $this->assertSame( [ 'squid' ]  , toArray( 'squid' ) ) ;

        // On an array, and only there, the two agree.
        $record = [ 'name' => 'Nemo' ] ;

        $this->assertSame( $record , arrayOrEmpty( $record ) ) ;
        $this->assertSame( $record , toArray( $record ) ) ;
    }
}
