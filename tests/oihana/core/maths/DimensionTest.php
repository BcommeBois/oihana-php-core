<?php

namespace tests\oihana\core\maths;

use oihana\core\maths\Dimension;

use PHPUnit\Framework\TestCase;

class DimensionTest extends TestCase
{
    public function testConstantsValues() : void
    {
        $this->assertSame( 'width'  , Dimension::WIDTH ) ;
        $this->assertSame( 'height' , Dimension::HEIGHT ) ;
    }

    public function testAllReturnsEveryKey() : void
    {
        $this->assertSame(
            [ Dimension::WIDTH , Dimension::HEIGHT ] ,
            Dimension::all()
        ) ;
    }

    public function testIsValidAcceptsKnownKeys() : void
    {
        $this->assertTrue( Dimension::isValid( 'width' ) ) ;
        $this->assertTrue( Dimension::isValid( 'height' ) ) ;
    }

    public function testIsValidRejectsUnknownKey() : void
    {
        $this->assertFalse( Dimension::isValid( 'depth' ) ) ;
    }

    public function testIsValidIsCaseSensitive() : void
    {
        $this->assertFalse( Dimension::isValid( 'WIDTH' ) ) ;
    }
}
