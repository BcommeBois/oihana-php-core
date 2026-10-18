<?php

namespace tests\oihana\core\maths;

use InvalidArgumentException;

use function oihana\core\maths\aspectRatio;

use PHPUnit\Framework\TestCase;

class AspectRatioTest extends TestCase
{
    public function testTheRatioIsSimplified() : void
    {
        $this->assertSame( '16:9'  , aspectRatio( 1920 , 1080 ) ) ;
        $this->assertSame( '16:9'  , aspectRatio( 1280 , 720 ) ) ;
        $this->assertSame( '32:27' , aspectRatio( 1280 , 1080 ) ) ;
        $this->assertSame( '4:3'   , aspectRatio( 1024 , 768 ) ) ;
    }

    public function testASquareAndAPortrait() : void
    {
        $this->assertSame( '1:1'  , aspectRatio( 1080 , 1080 ) ) ;
        $this->assertSame( '9:16' , aspectRatio( 1080 , 1920 ) ) ;
    }

    public function testTheSeparatorCanBeChanged() : void
    {
        $this->assertSame( '16/9' , aspectRatio( 1920 , 1080 , '/' ) ) ;
        $this->assertSame( '16x9' , aspectRatio( 1920 , 1080 , 'x' ) ) ;
    }

    public function testAZeroWidthIsRefused() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessage( 'The width must be greater than 0, 0 given.' ) ;

        aspectRatio( 0 , 1080 ) ;
    }

    public function testANegativeHeightIsRefused() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessage( 'The height must be greater than 0, -1080 given.' ) ;

        aspectRatio( 1920 , -1080 ) ;
    }

    /**
     * gcd( 0 , 0 ) is undefined : the width is refused first.
     */
    public function testBothZeroAreRefused() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        $this->expectExceptionMessage( 'The width must be greater than 0' ) ;

        aspectRatio( 0 , 0 ) ;
    }
}
