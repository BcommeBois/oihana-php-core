<?php

namespace tests\oihana\core\objects;

use function oihana\core\objects\values;

use PHPUnit\Framework\TestCase;

class ValuesTest extends TestCase
{
    public function testReturnsPropertyValues() : void
    {
        $user = (object) [ 'id' => 42 , 'name' => 'Alice' ] ;
        $this->assertSame( [ 42 , 'Alice' ] , values( $user ) ) ;
    }

    public function testEmptyObject() : void
    {
        $this->assertSame( [] , values( (object) [] ) ) ;
    }

    public function testOnlyPublicProperties() : void
    {
        $object = new class
        {
            public string $visible = 'yes' ;
            protected string $hidden = 'no' ;
        };
        $this->assertSame( [ 'yes' ] , values( $object ) ) ;
    }
}
