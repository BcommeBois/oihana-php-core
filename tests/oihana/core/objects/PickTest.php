<?php

namespace tests\oihana\core\objects;

use stdClass;

use function oihana\core\objects\pick;

use PHPUnit\Framework\TestCase;

class PickTest extends TestCase
{
    public function testKeepsOnlyListedProperties() : void
    {
        $user = (object) [ 'id' => 42 , 'name' => 'Alice' , 'email' => 'a@x.io' ] ;
        $this->assertEquals( (object) [ 'id' => 42 , 'name' => 'Alice' ] , pick( $user , [ 'id' , 'name' ] ) ) ;
    }

    public function testIgnoresMissingKeys() : void
    {
        $user = (object) [ 'id' => 42 ] ;
        $this->assertEquals( (object) [ 'id' => 42 ] , pick( $user , [ 'id' , 'unknown' ] ) ) ;
    }

    public function testReturnsStdClass() : void
    {
        $this->assertInstanceOf( stdClass::class , pick( (object) [ 'a' => 1 ] , [ 'a' ] ) ) ;
    }

    public function testDoesNotMutateSource() : void
    {
        $user = (object) [ 'id' => 42 , 'name' => 'Alice' ] ;
        pick( $user , [ 'id' ] ) ;
        $this->assertEquals( (object) [ 'id' => 42 , 'name' => 'Alice' ] , $user ) ;
    }

    public function testEmptyKeys() : void
    {
        $this->assertEquals( new stdClass() , pick( (object) [ 'a' => 1 ] , [] ) ) ;
    }
}
