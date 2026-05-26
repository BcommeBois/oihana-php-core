<?php

namespace tests\oihana\core\encoding ;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\encoding\base64UrlDecode;

final class Base64UrlDecodeTest extends TestCase
{
    // ---- Empty input --------------------------------------------------------

    public function testEmptyStringReturnsEmptyString() :void
    {
        $this->assertSame( '' , base64UrlDecode( '' ) ) ;
    }

    // ---- RFC 4648 §10 reference vectors (URL-safe, unpadded) ---------------

    public static function rfcVectorsProvider() :array
    {
        return [
            [ 'Zg'       , 'f'      ] ,
            [ 'Zm8'      , 'fo'     ] ,
            [ 'Zm9v'     , 'foo'    ] ,
            [ 'Zm9vYg'   , 'foob'   ] ,
            [ 'Zm9vYmE'  , 'fooba'  ] ,
            [ 'Zm9vYmFy' , 'foobar' ] ,
        ] ;
    }

    #[DataProvider('rfcVectorsProvider')]
    public function testRfcReferenceVectors( string $encoded , string $expected ) :void
    {
        $this->assertSame( $expected , base64UrlDecode( $encoded ) ) ;
    }

    // ---- URL-safe alphabet --------------------------------------------------

    public function testUrlSafeAlphabetIsDecoded() :void
    {
        // "-_8" must decode to 0xFB,0xFF (counterpart of "+/8=" in standard b64).
        $this->assertSame( "\xFB\xFF" , base64UrlDecode( '-_8' ) ) ;
    }

    // ---- Padding tolerance --------------------------------------------------

    public function testTrailingPaddingIsAccepted() :void
    {
        $this->assertSame( 'hello' , base64UrlDecode( 'aGVsbG8'  ) ) ; // canonical, no padding
        $this->assertSame( 'hello' , base64UrlDecode( 'aGVsbG8=' ) ) ; // tolerated padded
        $this->assertSame( 'he'    , base64UrlDecode( 'aGU'      ) ) ;
        $this->assertSame( 'he'    , base64UrlDecode( 'aGU='     ) ) ;
        $this->assertSame( 'h'     , base64UrlDecode( 'aA'       ) ) ;
        $this->assertSame( 'h'     , base64UrlDecode( 'aA=='     ) ) ;
    }

    // ---- Strict alphabet rejection -----------------------------------------

    public static function invalidInputProvider() :array
    {
        return [
            'standard base64 `+`'        => [ 'aGVsbG8+'    ] ,
            'standard base64 `/`'        => [ 'aGVsbG8/'    ] ,
            'whitespace inside'          => [ 'aGVs bG8'    ] ,
            'newline inside'             => [ "aGVs\nbG8"   ] ,
            'tab inside'                 => [ "aGVs\tbG8"   ] ,
            'leading space'              => [ ' aGVsbG8'    ] ,
            'trailing space'             => [ 'aGVsbG8 '    ] ,
            'non-ASCII byte'             => [ 'aGVsbG8é'    ] ,
            'null byte'                  => [ "aGVs\x00bG8" ] ,
            'control char'               => [ "aGVs\x01bG8" ] ,
            'unknown symbol'             => [ 'aGVsbG8!'    ] ,
            'padding inside string'      => [ 'aG=sbG8'     ] ,
            'too many padding chars'     => [ 'aGVsbG8==='  ] ,
            'only padding'               => [ '==='         ] ,
            'only padding (single)'      => [ '='           ] ,
            'padding-only token'         => [ '=='          ] ,
        ] ;
    }

    #[DataProvider('invalidInputProvider')]
    public function testInvalidInputReturnsFalse( string $value ) :void
    {
        $this->assertFalse( base64UrlDecode( $value ) ) ;
    }

    // ---- Malformed length ---------------------------------------------------

    public function testSingleCharacterIsInvalid() :void
    {
        // A single base64 char carries less than one byte ; it cannot be a valid
        // payload, so the strict decoder must reject it.
        $this->assertFalse( base64UrlDecode( 'a' ) ) ;
    }

    public function testFiveCharsInputIsInvalid() :void
    {
        // 5 % 4 == 1 → no valid base64 length corresponds to 5 raw chars.
        $this->assertFalse( base64UrlDecode( 'abcde' ) ) ;
    }
}
