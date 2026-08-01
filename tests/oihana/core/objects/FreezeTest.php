<?php

namespace tests\oihana\core\objects;

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
}
