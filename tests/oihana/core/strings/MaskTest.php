<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\mask;

use PHPUnit\Framework\TestCase;

class MaskTest extends TestCase
{
    public function testMasksWithDefaults() : void
    {
        $this->assertSame( '************4242' , mask( '4242424242424242' ) ) ;
    }

    public function testKeepsBothEndsVisible() : void
    {
        $this->assertSame( 's****t' , mask( 'secret' , 1 , 1 ) ) ;
    }

    public function testCustomMaskCharAndMultibyte() : void
    {
        $this->assertSame( 'jo••••••.com' , mask( 'john@doe.com' , 2 , 4 , '•' ) ) ;
    }

    public function testReturnsSourceWhenRegionsOverlap() : void
    {
        $this->assertSame( 'abc' , mask( 'abc' , 2 , 2 ) ) ;
    }

    public function testNegativeVisibleCountsAreClamped() : void
    {
        $this->assertSame( '****', mask( 'wxyz' , -3 , 0 ) ) ;
    }

    public function testIsGraphemeSafe() : void
    {
        // Accented letters are single grapheme clusters and must not be split.
        $this->assertSame( 'cé****é' , mask( 'célébré' , 2 , 1 ) ) ;
    }
}
