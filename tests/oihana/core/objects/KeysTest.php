<?php

namespace tests\oihana\core\objects;

use function oihana\core\objects\keys;

use PHPUnit\Framework\TestCase;

class KeysTest extends TestCase
{
    public function testReturnsPropertyNames() : void
    {
        $user = (object) [ 'id' => 42 , 'name' => 'Alice' ] ;
        $this->assertSame( [ 'id' , 'name' ] , keys( $user ) ) ;
    }

    public function testEmptyObject() : void
    {
        $this->assertSame( [] , keys( (object) [] ) ) ;
    }

    public function testOnlyPublicProperties() : void
    {
        $object = new class
        {
            public int $visible = 1 ;
            protected int $hidden = 2 ;
            private int $secret = 3 ;
        };
        $this->assertSame( [ 'visible' ] , keys( $object ) ) ;
    }
}
