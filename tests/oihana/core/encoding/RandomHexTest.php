<?php

namespace tests\oihana\core\encoding ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

use function oihana\core\encoding\randomHex;

final class RandomHexTest extends TestCase
{
    /**
     * @throws RandomException
     */
    public function testDefaultReturnsTwoHundredFiftySixBitsOfEntropy() :void
    {
        $token = randomHex() ;
        $this->assertSame( 64 , strlen( $token ) ) ;
    }

    /**
     * @throws RandomException
     */
    public function testOutputIsLowercaseHex() :void
    {
        $token = randomHex( 32 ) ;
        $this->assertMatchesRegularExpression( '/^[0-9a-f]+$/' , $token ) ;
    }

    /**
     * @throws RandomException
     */
    public function testCustomByteLength() :void
    {
        foreach ( [ 1 , 8 , 16 , 24 , 48 , 64 ] as $bytes )
        {
            $token = randomHex( $bytes ) ;
            $this->assertSame( $bytes * 2 , strlen( $token ) , "Hex length mismatch for \$bytes=$bytes" ) ;
            $this->assertMatchesRegularExpression( '/^[0-9a-f]+$/' , $token ) ;
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
            $tokens[] = randomHex( 32 ) ;
        }
        $this->assertCount( 64 , array_unique( $tokens ) , 'Generated tokens must all be distinct' ) ;
    }

    public function testZeroBytesThrows() :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        randomHex( 0 ) ;
    }

    public function testNegativeBytesThrows() :void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        randomHex( -1 ) ;
    }
}
