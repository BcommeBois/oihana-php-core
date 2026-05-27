<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class GetQuoteCharTest extends TestCase
{
    public function testEmpty(): void
    {
        $this->assertNull(getQuoteChar(''));
    }

    public function testSingleChar(): void
    {
        $this->assertNull(getQuoteChar('"'));
        $this->assertNull(getQuoteChar('«'));
    }

    public function testDoubleQuotes(): void
    {
        $this->assertSame('"', getQuoteChar('"hello"'));
        $this->assertSame('"', getQuoteChar('""'));
    }

    public function testSingleQuotes(): void
    {
        $this->assertSame("'", getQuoteChar("'hello'"));
    }

    public function testBackticks(): void
    {
        $this->assertSame('`', getQuoteChar('`hello`'));
    }

    public function testFrenchGuillemets(): void
    {
        $this->assertSame('«', getQuoteChar('«hello»'));
    }

    public function testEnglishTypographicQuotes(): void
    {
        $this->assertSame('“', getQuoteChar('“hello”'));
        $this->assertSame('‘', getQuoteChar('‘hello’'));
    }

    public function testUnmatchedReturnsNull(): void
    {
        $this->assertNull(getQuoteChar('"hello'));
        $this->assertNull(getQuoteChar('hello"'));
        $this->assertNull(getQuoteChar('»hello«'));
    }

    public function testUnquotedReturnsNull(): void
    {
        $this->assertNull(getQuoteChar('hello'));
    }
}
