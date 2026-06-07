<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\chunk;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ChunkTest extends TestCase
{
    // ------------------------------
    // ASCII tests
    // ------------------------------

    public function testBasicAscii(): void
    {
        $this->assertSame('56 54 48', chunk('565448', 2));
        $this->assertSame('15 25', chunk('1525', 2));
        $this->assertSame('25', chunk('25', 2));
        $this->assertSame('123:456:789', chunk('123456789', 3, ':'));
    }

    public function testMultipleOccurrencesAscii(): void
    {
        $this->assertSame('foo bar foo', chunk('foobarfoo', 3, ' '));
    }

    public function testSizeGreaterThanString(): void
    {
        $this->assertSame('123', chunk('123', 5));
    }

    public function testZeroOrNegativeSizeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        chunk('123', 0);

        $this->expectException(InvalidArgumentException::class);
        chunk('123', -2);
    }

    // ------------------------------
    // Multibyte / accented characters
    // ------------------------------

    public function testAccentedCharacters(): void
    {
        $this->assertSame('éà üö', chunk('éàüö', 2, ' ' , true ));
        $this->assertSame('café', chunk('café', 4, ' ' , true  )); // string shorter than size → unchanged
    }

    // ------------------------------
    // Emoji / surrogate pairs
    // ------------------------------

    public function testEmoji(): void
    {
        $this->assertSame('😀😃 😄😁', chunk('😀😃😄😁', 2, ' ' , true  ));
        $this->assertSame('💛❤️ 💛', chunk('💛❤️💛', 2, ' ' , true ));
    }

    // ------------------------------
    // Null or empty string
    // ------------------------------

    public function testNullOrEmpty(): void
    {
        $this->assertSame('', chunk(null, 2));
        $this->assertSame('', chunk('', 2));
    }
}