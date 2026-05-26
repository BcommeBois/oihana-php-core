<?php

namespace tests\oihana\core\encoding ;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\encoding\base64UrlEncode;

final class Base64UrlEncodeTest extends TestCase
{
    // ---- Empty input --------------------------------------------------------

    public function testEmptyStringReturnsEmptyString() :void
    {
        $this->assertSame( '' , base64UrlEncode( '' ) ) ;
    }

    // ---- RFC 4648 §10 reference vectors (URL-safe, unpadded) ---------------

    public static function rfcVectorsProvider() :array
    {
        return [
            [ ''       , ''         ] ,
            [ 'f'      , 'Zg'       ] ,
            [ 'fo'     , 'Zm8'      ] ,
            [ 'foo'    , 'Zm9v'     ] ,
            [ 'foob'   , 'Zm9vYg'   ] ,
            [ 'fooba'  , 'Zm9vYmE'  ] ,
            [ 'foobar' , 'Zm9vYmFy' ] ,
        ] ;
    }

    #[DataProvider('rfcVectorsProvider')]
    public function testRfcReferenceVectors( string $binary , string $expected ) :void
    {
        $this->assertSame( $expected , base64UrlEncode( $binary ) ) ;
    }

    // ---- URL-safe alphabet --------------------------------------------------

    public function testOutputUsesUrlSafeAlphabet() :void
    {
        // 0xFB,0xFF would encode to "+/8=" in standard base64.
        // In base64url it must become "-_8" (no padding, '-' and '_').
        $this->assertSame( '-_8' , base64UrlEncode( "\xFB\xFF" ) ) ;
    }

    public function testOutputNeverContainsPlusSlashOrPadding() :void
    {
        // Try a payload that produces all three replaced characters in standard b64.
        $binary = '';
        for ( $i = 0 ; $i < 256 ; $i++ )
        {
            $binary .= chr( $i ) ;
        }

        $encoded = base64UrlEncode( $binary ) ;

        $this->assertMatchesRegularExpression( '/^[A-Za-z0-9_\-]+$/' , $encoded ) ;
        $this->assertStringNotContainsString( '+' , $encoded ) ;
        $this->assertStringNotContainsString( '/' , $encoded ) ;
        $this->assertStringNotContainsString( '=' , $encoded ) ;
    }

    // ---- Binary safety ------------------------------------------------------

    public function testNullByteIsPreserved() :void
    {
        $this->assertSame( 'AA' , base64UrlEncode( "\x00" ) ) ;
        $this->assertSame( 'AAA' , base64UrlEncode( "\x00\x00" ) ) ;
        $this->assertSame( 'AAAA' , base64UrlEncode( "\x00\x00\x00" ) ) ;
    }

    public function testUtf8MultibyteInput() :void
    {
        // "é" = 0xC3 0xA9 ; standard b64 = "w6k=" ; url-safe unpadded = "w6k"
        $this->assertSame( 'w6k' , base64UrlEncode( 'é' ) ) ;
    }

    // ---- Padding stripping --------------------------------------------------

    public function testNoPaddingIsEmitted() :void
    {
        // 1 byte → standard b64 needs "==" padding.
        $this->assertSame( 'YQ' , base64UrlEncode( 'a' ) ) ;
        // 2 bytes → standard b64 needs "=" padding.
        $this->assertSame( 'YWI' , base64UrlEncode( 'ab' ) ) ;
        // 3 bytes → no padding in standard b64.
        $this->assertSame( 'YWJj' , base64UrlEncode( 'abc' ) ) ;
    }
}
