<?php

namespace tests\oihana\core\maths;

use function oihana\core\maths\aspectFit;

use PHPUnit\Framework\TestCase;

class AspectFitTest extends TestCase
{
    public function testDeriveHeightFromTargetWidth() : void
    {
        $this->assertSame( [ 'width' => 1280 , 'height' => 720 ] , aspectFit( 1920 , 1080 , targetWidth: 1280 ) ) ;
    }

    public function testDeriveWidthFromTargetHeight() : void
    {
        $this->assertSame( [ 'width' => 960 , 'height' => 540 ] , aspectFit( 1920 , 1080 , targetHeight: 540 ) ) ;
    }

    public function testTargetWidthTakesPrecedenceOverTargetHeight() : void
    {
        $this->assertSame( [ 'width' => 1280 , 'height' => 720 ] , aspectFit( 1920 , 1080 , 1280 , 99 ) ) ;
    }

    public function testNoTargetReturnsOriginalPair() : void
    {
        $this->assertSame( [ 'width' => 1920 , 'height' => 1080 ] , aspectFit( 1920 , 1080 ) ) ;
    }

    public function testRoundingToNearestPixel() : void
    {
        // 10 × 10 / 3 = 33.33… rounds to 33 (width-driven branch).
        $this->assertSame( [ 'width' => 10 , 'height' => 33 ] , aspectFit( 3 , 10 , targetWidth: 10 ) ) ;
        // 10 × 10 / 3 = 33.33… rounds to 33 (height-driven branch).
        $this->assertSame( [ 'width' => 33 , 'height' => 10 ] , aspectFit( 10 , 3 , targetHeight: 10 ) ) ;
    }

    public function testNonPositiveDimensionsReturnTargetsOrOriginals() : void
    {
        // Undefined ratio: provided targets win, missing ones fall back to the originals.
        $this->assertSame( [ 'width' => 1280 , 'height' => 1080 ] , aspectFit( 0 , 1080 , targetWidth: 1280 ) ) ;
        $this->assertSame( [ 'width' => 1920 , 'height' => 540 ]  , aspectFit( 1920 , 0 , targetHeight: 540 ) ) ;
        $this->assertSame( [ 'width' => -5 , 'height' => -10 ]    , aspectFit( -5 , -10 ) ) ;
    }
}
