<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\truncate;

use PHPUnit\Framework\TestCase;

class TruncateTest extends TestCase
{
    public function testTruncatesWithDefaultEllipsis() : void
    {
        $this->assertSame( 'The quick…' , truncate( 'The quick brown fox' , 9 ) ) ;
    }

    public function testReturnsSourceWhenShorter() : void
    {
        $this->assertSame( 'Hello' , truncate( 'Hello' , 10 ) ) ;
    }

    public function testReturnsSourceWhenEqualLength() : void
    {
        $this->assertSame( 'Hello' , truncate( 'Hello' , 5 ) ) ;
    }

    public function testCustomEllipsis() : void
    {
        $this->assertSame( 'Café...' , truncate( 'Café société' , 4 , '...' ) ) ;
    }

    public function testIsGraphemeSafe() : void
    {
        // 👍🏽 is a single grapheme cluster (emoji + skin tone modifier).
        $this->assertSame( '👍🏽…' , truncate( '👍🏽 ok' , 1 ) ) ;
    }

    public function testZeroLength() : void
    {
        $this->assertSame( '…' , truncate( 'abc' , 0 ) ) ;
    }
}
