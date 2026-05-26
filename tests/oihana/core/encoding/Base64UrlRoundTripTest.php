<?php

namespace tests\oihana\core\encoding ;

use PHPUnit\Framework\TestCase;

use Random\RandomException;
use function oihana\core\encoding\base64UrlDecode;
use function oihana\core\encoding\base64UrlEncode;

final class Base64UrlRoundTripTest extends TestCase
{
    /**
     * @throws RandomException
     */
    public function testRoundTripAllLengthsZeroToThirtyTwo() :void
    {
        for ( $length = 0 ; $length <= 32 ; $length++ )
        {
            $binary  = $length === 0 ? '' : random_bytes( $length ) ;
            $encoded = base64UrlEncode( $binary ) ;
            $decoded = base64UrlDecode( $encoded ) ;

            $this->assertSame
            (
                $binary ,
                $decoded ,
                "Round-trip failed at length $length (encoded: '$encoded')"
            ) ;

            $this->assertMatchesRegularExpression
            (
                '/^[A-Za-z0-9_\-]*$/' ,
                $encoded ,
                "Encoded output at length $length must be URL-safe and unpadded"
            ) ;
        }
    }

    /**
     * @throws RandomException
     */
    public function testRoundTripLargePayloads() :void
    {
        foreach ( [ 256 , 1024 , 4096 ] as $length )
        {
            $binary  = random_bytes( $length ) ;
            $encoded = base64UrlEncode( $binary ) ;
            $decoded = base64UrlDecode( $encoded ) ;

            $this->assertSame( $binary , $decoded , "Round-trip failed at length $length" ) ;
        }
    }

    public function testRoundTripCoversFullByteRange() :void
    {
        $binary = '';
        for ( $i = 0 ; $i < 256 ; $i++ )
        {
            $binary .= chr( $i ) ;
        }

        $this->assertSame( $binary , base64UrlDecode( base64UrlEncode( $binary ) ) ) ;
    }
}
