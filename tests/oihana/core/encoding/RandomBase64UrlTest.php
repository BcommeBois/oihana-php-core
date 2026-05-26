<?php

namespace tests\oihana\core\encoding ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

use function oihana\core\encoding\base64UrlDecode;
use function oihana\core\encoding\randomBase64Url;

final class RandomBase64UrlTest extends TestCase
{
    /**
     * @throws RandomException
     */
    public function testDefaultReturnsTwoHundredFiftySixBitsOfEntropy() :void
    {
        $token = randomBase64Url() ;

        // ceil( 32 * 4 / 3 ) = 43 chars without padding.
        $this->assertSame( 43 , strlen( $token ) ) ;

        $decoded = base64UrlDecode( $token ) ;
        $this->assertNotFalse( $decoded ) ;
        $this->assertSame( 32 , strlen( $decoded ) ) ;
    }

    /**
     * @throws RandomException
     */
    public function testOutputIsUrlSafeAndUnpadded() :void
    {
        $token = randomBase64Url( 32 ) ;
        $this->assertMatchesRegularExpression( '/^[A-Za-z0-9_\-]+$/' , $token ) ;
        $this->assertStringNotContainsString( '+' , $token ) ;
        $this->assertStringNotContainsString( '/' , $token ) ;
        $this->assertStringNotContainsString( '=' , $token ) ;
    }

    /**
     * @throws RandomException
     */
    public function testCustomByteLength() :void
    {
        foreach ( [ 1 , 8 , 16 , 24 , 48 , 64 ] as $bytes )
        {
            $token   = randomBase64Url( $bytes ) ;
            $decoded = base64UrlDecode( $token ) ;

            $this->assertNotFalse( $decoded , "Token must round-trip for \$bytes=$bytes" ) ;
            $this->assertSame( $bytes , strlen( $decoded ) , "Decoded byte length mismatch for \$bytes=$bytes" ) ;
            $this->assertMatchesRegularExpression( '/^[A-Za-z0-9_\-]+$/' , $token ) ;
        }
    }

    /**
     * @throws RandomException
     */
    public function testSuccessiveCallsAreDistinct() :void
    {
        $tokens = [] ;
        for ( $i = 0 ; $i < 64 ; $i++ )
        {
            $tokens[] = randomBase64Url( 32 ) ;
        }
        $this->assertCount( 64 , array_unique( $tokens ) , 'Generated tokens must all be distinct' ) ;
    }

    public function testZeroBytesThrows() :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        randomBase64Url( 0 ) ;
    }

    public function testNegativeBytesThrows() :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        randomBase64Url( -1 ) ;
    }
}
