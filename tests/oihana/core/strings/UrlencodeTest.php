<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\urlencode;

use PHPUnit\Framework\TestCase;

class UrlencodeTest extends TestCase
{
    public function testReservedCharactersAreRestoredToLiteralForm() : void
    {
        $uri = 'https://example.com/foo?bar=baz&qux=1' ;
        $this->assertSame( $uri , urlencode( $uri ) ) ;
    }

    public function testSpaceIsEncodedAsPlus() : void
    {
        $this->assertSame( 'hello+world!' , urlencode( 'hello world!' ) ) ;
    }

    public function testKeepsUnreservedCharacters() : void
    {
        $this->assertSame( 'AZaz09-_.' , urlencode( 'AZaz09-_.' ) ) ;
    }

    public function testRestoresEachReservedCharacter() : void
    {
        $this->assertSame( "!*'();:@&=+$,/?%#[]" , urlencode( "!*'();:@&=+$,/?%#[]" ) ) ;
    }
}
