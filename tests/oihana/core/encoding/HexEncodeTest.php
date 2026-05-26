<?php

namespace tests\oihana\core\encoding ;

use PHPUnit\Framework\TestCase;

use function oihana\core\encoding\hexEncode;

final class HexEncodeTest extends TestCase
{
    public function testEmptyStringReturnsEmptyString() :void
    {
        $this->assertSame( '' , hexEncode( '' ) ) ;
    }

    public function testAsciiInput() :void
    {
        $this->assertSame( '616263' , hexEncode( 'abc' ) ) ;
    }

    public function testNullByteAndHighByteAreEncoded() :void
    {
        $this->assertSame( '00ff' , hexEncode( "\x00\xFF" ) ) ;
    }

    public function testOutputIsLowercase() :void
    {
        $this->assertSame( 'deadbeef' , hexEncode( "\xDE\xAD\xBE\xEF" ) ) ;
    }

    public function testOutputLengthIsDoubleInput() :void
    {
        for ( $length = 0 ; $length <= 16 ; $length++ )
        {
            $binary = str_repeat( "\xAB" , $length ) ;
            $this->assertSame( $length * 2 , strlen( hexEncode( $binary ) ) ) ;
        }
    }

    public function testFullByteRange() :void
    {
        $binary   = '' ;
        $expected = '' ;
        for ( $i = 0 ; $i < 256 ; $i++ )
        {
            $binary   .= chr( $i ) ;
            $expected .= str_pad( dechex( $i ) , 2 , '0' , STR_PAD_LEFT ) ;
        }
        $this->assertSame( $expected , hexEncode( $binary ) ) ;
    }
}
