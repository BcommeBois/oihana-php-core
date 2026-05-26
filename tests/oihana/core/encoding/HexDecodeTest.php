<?php

namespace tests\oihana\core\encoding ;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function oihana\core\encoding\hexDecode;

final class HexDecodeTest extends TestCase
{
    public function testEmptyStringReturnsEmptyString() :void
    {
        $this->assertSame( '' , hexDecode( '' ) ) ;
    }

    public function testLowercaseDecoding() :void
    {
        $this->assertSame( 'abc' , hexDecode( '616263' ) ) ;
    }

    public function testUppercaseDecoding() :void
    {
        $this->assertSame( "\x00\xFF" , hexDecode( '00FF' ) ) ;
    }

    public function testMixedCaseDecoding() :void
    {
        $this->assertSame( "\xDE\xAD\xBE\xEF" , hexDecode( 'DeAdBeEf' ) ) ;
    }

    public function testNullByteIsDecoded() :void
    {
        $this->assertSame( "\x00\x00\x00" , hexDecode( '000000' ) ) ;
    }

    // ---- Strict alphabet / length rejection ---------------------------------

    public static function invalidInputProvider() :array
    {
        return [
            'odd length (1)'         => [ '6'         ] ,
            'odd length (3)'         => [ '616'       ] ,
            'odd length (5)'         => [ 'DeAdB'     ] ,
            'invalid char Z'         => [ '6Z'        ] ,
            'invalid char G'         => [ 'AG'        ] ,
            'whitespace inside'      => [ '61 62'     ] ,
            'leading space'          => [ ' 61'       ] ,
            'trailing space'         => [ '61 '       ] ,
            'newline inside'         => [ "61\n62"    ] ,
            'tab inside'             => [ "61\t62"    ] ,
            'null byte'              => [ "61\x0062"  ] ,
            'non-ASCII'              => [ '61é2'      ] ,
            'punctuation'            => [ '61-62'     ] ,
            'prefix 0x'              => [ '0x6162'    ] ,
        ] ;
    }

    #[DataProvider('invalidInputProvider')]
    public function testInvalidInputReturnsFalse( string $value ) :void
    {
        $this->assertFalse( hexDecode( $value ) ) ;
    }
}
