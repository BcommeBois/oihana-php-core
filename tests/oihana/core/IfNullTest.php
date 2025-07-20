<?php

namespace oihana\core ;

use PHPUnit\Framework\TestCase;

class IfNullTest extends TestCase
{
    public function testReturnsValueWhenNotNull()
    {
        $this->assertEquals("test", ifNull( "test" , "default" ) );
    }

    public function testReturnsDefaultWhenValueIsNull()
    {
        $this->assertEquals("default", ifNull( null, "default" ) );
    }

    public function testReturnsNullWhenBothAreNull()
    {
        $this->assertNull( ifNull( null ) );
    }

    public function testReturnsValueWhenItIsAnArray()
    {
        $this->assertEquals( [1, 2, 3] , ifNull(  [1, 2, 3] , [4, 5, 6] ) ) ;
    }

    public function testReturnsDefaultWhenItIsAnArray()
    {
        $this->assertEquals( [4, 5, 6] , ifNull(null , [4, 5, 6] ) ) ;
    }
}