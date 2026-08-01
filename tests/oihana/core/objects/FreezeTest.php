<?php

namespace tests\oihana\core\objects;

use InvalidArgumentException;

use oihana\core\arrays\CleanFlag;

use function oihana\core\objects\freeze;

use PHPUnit\Framework\TestCase;

class FreezeTest extends TestCase
{
    public function testKeepsOnlyListedProperties() : void
    {
        $thing = (object) [ 'id' => 42 , 'name' => 'Alice' , 'email' => 'a@x.io' ] ;
        $this->assertSame( [ 'id' => 42 , 'name' => 'Alice' ] , freeze( $thing , [ 'id' , 'name' ] ) ) ;
    }

    public function testDropsNullValues() : void
    {
        $thing = (object) [ 'id' => 42 , 'name' => null ] ;
        $this->assertSame( [ 'id' => 42 ] , freeze( $thing , [ 'id' , 'name' ] ) ) ;
    }

    public function testKeepsFalsyValues() : void
    {
        $thing = (object) [ 'score' => 0 , 'ratio' => 0.0 , 'label' => '' , 'active' => false , 'tags' => [] ] ;
        $this->assertSame
        (
            [ 'score' => 0 , 'ratio' => 0.0 , 'label' => '' , 'active' => false , 'tags' => [] ] ,
            freeze( $thing , [ 'score' , 'ratio' , 'label' , 'active' , 'tags' ] )
        ) ;
    }

    public function testIgnoresMissingProperties() : void
    {
        $thing = (object) [ 'id' => 42 ] ;
        $this->assertSame( [ 'id' => 42 ] , freeze( $thing , [ 'id' , 'unknown' ] ) ) ;
    }

    public function testFollowsTheFieldsOrder() : void
    {
        $thing = (object) [ 'a' => 1 , 'b' => 2 , 'c' => 3 ] ;
        $this->assertSame( [ 'c' => 3 , 'a' => 1 , 'b' => 2 ] , freeze( $thing , [ 'c' , 'a' , 'b' ] ) ) ;
    }

    public function testEmptyFields() : void
    {
        $this->assertSame( [] , freeze( (object) [ 'a' => 1 ] , [] ) ) ;
    }

    public function testDoesNotMutateSource() : void
    {
        $thing = (object) [ 'id' => 42 , 'name' => 'Alice' ] ;
        freeze( $thing , [ 'id' ] ) ;
        $this->assertEquals( (object) [ 'id' => 42 , 'name' => 'Alice' ] , $thing ) ;
    }

    public function testSkipsUninitializedAndInaccessibleProperties() : void
    {
        $thing = new class
        {
            public string  $name   = 'Alice' ;
            public ?string $url    ; // typed, never initialized
            private string $secret = 'hidden' ;
        };

        $this->assertSame( [ 'name' => 'Alice' ] , freeze( $thing , [ 'name' , 'url' , 'secret' ] ) ) ;
    }

    public function testHonoursMagicAccessors() : void
    {
        $thing = new class
        {
            public string $name = 'Alice' ;

            /** @var array<string,mixed> */
            private array $virtual = [ 'url' => 'https://example.org' , 'image' => null ] ;

            public function __isset( string $key ) : bool
            {
                return isset( $this->virtual[ $key ] ) ;
            }

            public function __get( string $key ) : mixed
            {
                return $this->virtual[ $key ] ?? null ;
            }
        };

        $this->assertSame
        (
            [ 'name' => 'Alice' , 'url' => 'https://example.org' ] ,
            freeze( $thing , [ 'name' , 'url' , 'image' ] )
        ) ;
    }

    // ------------- renaming

    public function testRenamesPropertiesWithStringKeys() : void
    {
        $thing = (object) [ '_key' => 'aeb1' , 'name' => 'Alice' ] ;
        $this->assertSame
        (
            [ '_key' => 'aeb1' , 'thingName' => 'Alice' ] ,
            freeze( $thing , [ '_key' , 'thingName' => 'name' ] )
        ) ;
    }

    public function testRenamingKeepsTheFieldsOrder() : void
    {
        $thing = (object) [ 'a' => 1 , 'b' => 2 ] ;
        $this->assertSame( [ 'beta' => 2 , 'alpha' => 1 ] , freeze( $thing , [ 'beta' => 'b' , 'alpha' => 'a' ] ) ) ;
    }

    public function testRenamingAMissingPropertyDropsTheTargetKey() : void
    {
        $thing = (object) [ 'a' => 1 ] ;
        $this->assertSame( [ 'alpha' => 1 ] , freeze( $thing , [ 'alpha' => 'a' , 'omega' => 'z' ] ) ) ;
    }

    public function testTheSamePropertyCanBeCopiedTwice() : void
    {
        $thing = (object) [ 'name' => 'Alice' ] ;
        $this->assertSame
        (
            [ 'name' => 'Alice' , 'label' => 'Alice' ] ,
            freeze( $thing , [ 'name' , 'label' => 'name' ] )
        ) ;
    }

    // ------------- flags

    public function testFlagsMainDiscardsEmptyStringsAndArrays() : void
    {
        $thing = (object) [ 'name' => 'Alice' , 'label' => '   ' , 'tags' => [] , 'score' => 0 , 'id' => null ] ;
        $this->assertSame
        (
            [ 'name' => 'Alice' , 'score' => 0 ] ,
            freeze( $thing , [ 'name' , 'label' , 'tags' , 'score' , 'id' ] , CleanFlag::MAIN )
        ) ;
    }

    public function testFlagsFalsyDiscardsZeroAndFalse() : void
    {
        $thing = (object) [ 'name' => 'Alice' , 'score' => 0 , 'active' => false , 'zero' => '0' ] ;
        $this->assertSame
        (
            [ 'name' => 'Alice' ] ,
            freeze( $thing , [ 'name' , 'score' , 'active' , 'zero' ] , CleanFlag::FALSY )
        ) ;
    }

    public function testZeroFlagsKeepsEverythingIncludingNulls() : void
    {
        $thing = (object) [ 'name' => 'Alice' , 'id' => null ] ;
        $this->assertSame
        (
            [ 'name' => 'Alice' , 'id' => null , 'unknown' => null ] ,
            freeze( $thing , [ 'name' , 'id' , 'unknown' ] , 0 )
        ) ;
    }

    public function testRecursiveFlagCleansNestedArrays() : void
    {
        $thing = (object) [ 'meta' => [ 'a' => 1 , 'b' => null ] ] ;
        $this->assertSame
        (
            [ 'meta' => [ 'a' => 1 ] ] ,
            freeze( $thing , [ 'meta' ] , CleanFlag::NULLS | CleanFlag::RECURSIVE )
        ) ;
    }

    public function testThrowsOnReturnNullFlag() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessage( 'CleanFlag::RETURN_NULL' ) ;
        freeze( (object) [ 'a' => 1 ] , [ 'a' ] , CleanFlag::NORMALIZE ) ;
    }

    public function testThrowsOnInvalidFlags() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        freeze( (object) [ 'a' => 1 ] , [ 'a' ] , 1 << 20 ) ;
    }

    // ------------- deep

    public function testDeepConvertsNestedObjects() : void
    {
        $thing = (object) [ 'name' => 'Alice' , 'address' => (object) [ 'city' => 'Paris' ] ] ;
        $this->assertSame
        (
            [ 'name' => 'Alice' , 'address' => [ 'city' => 'Paris' ] ] ,
            freeze( $thing , [ 'name' , 'address' ] , deep : true )
        ) ;
    }

    public function testDeepDetachesTheSnapshotFromTheSource() : void
    {
        $address = (object) [ 'city' => 'Paris' ] ;
        $thing   = (object) [ 'address' => $address ] ;

        $shallow = freeze( $thing , [ 'address' ] ) ;
        $frozen  = freeze( $thing , [ 'address' ] , deep : true ) ;

        $address->city = 'Lyon' ;

        $this->assertSame( 'Lyon'  , $shallow[ 'address' ]->city   ) ; // shared instance
        $this->assertSame( 'Paris' , $frozen [ 'address' ][ 'city' ] ) ; // detached copy
    }

    public function testDeepConvertsObjectsNestedInArrays() : void
    {
        $thing = (object) [ 'authors' => [ (object) [ 'name' => 'Alice' ] ] ] ;
        $this->assertSame
        (
            [ 'authors' => [ [ 'name' => 'Alice' ] ] ] ,
            freeze( $thing , [ 'authors' ] , deep : true )
        ) ;
    }

    public function testDeepLeavesScalarsUntouched() : void
    {
        $thing = (object) [ 'name' => 'Alice' , 'score' => 42 ] ;
        $this->assertSame( [ 'name' => 'Alice' , 'score' => 42 ] , freeze( $thing , [ 'name' , 'score' ] , deep : true ) ) ;
    }

    public function testDeepCombinedWithFlags() : void
    {
        $thing = (object) [ 'meta' => (object) [ 'a' => 1 , 'b' => null ] ] ;
        $this->assertSame
        (
            [ 'meta' => [ 'a' => 1 ] ] ,
            freeze( $thing , [ 'meta' ] , CleanFlag::NULLS | CleanFlag::RECURSIVE , true )
        ) ;
    }
}
