<?php declare(strict_types=1);

namespace tests\oihana\core\strings;

use function oihana\core\strings\unquote;

use PHPUnit\Framework\TestCase;

class UnquoteTest extends TestCase
{
    public function testEmptyString(): void
    {
        $this->assertSame('', unquote(''));
    }

    public function testDoubleQuotes(): void
    {
        $this->assertSame('hello', unquote('"hello"'));
    }

    public function testSingleQuotes(): void
    {
        $this->assertSame('hello', unquote("'hello'"));
    }

    public function testBackticks(): void
    {
        $this->assertSame('hello', unquote('`hello`'));
    }

    public function testFrenchGuillemets(): void
    {
        $this->assertSame('hello', unquote('«hello»'));
    }

    public function testEnglishTypographicDoubleQuotes(): void
    {
        $this->assertSame('hello', unquote('“hello”'));
    }

    public function testEnglishTypographicSingleQuotes(): void
    {
        $this->assertSame('hello', unquote('‘hello’'));
    }

    public function testUnmatchedLeftIsNoop(): void
    {
        $this->assertSame('"hello', unquote('"hello'));
    }

    public function testUnmatchedRightIsNoop(): void
    {
        $this->assertSame('hello"', unquote('hello"'));
    }

    public function testMixedQuotesIsNoop(): void
    {
        $this->assertSame('"hello\'', unquote('"hello\''));
        $this->assertSame('»hello«', unquote('»hello«'));
    }

    public function testTwoQuotesReturnsEmpty(): void
    {
        $this->assertSame('', unquote('""'));
        $this->assertSame('', unquote("''"));
        $this->assertSame('', unquote('«»'));
    }

    public function testOnlyOneLayerStripped(): void
    {
        $this->assertSame('"foo"', unquote('""foo""'));
        $this->assertSame("'foo'", unquote("''foo''"));
    }

    public function testNoQuotesIsNoop(): void
    {
        $this->assertSame('hello', unquote('hello'));
    }

    public function testSingleCharIsNoop(): void
    {
        $this->assertSame('"', unquote('"'));
        $this->assertSame("'", unquote("'"));
    }

    public function testInnerQuotesPreserved(): void
    {
        $this->assertSame('a"b', unquote('"a"b"'));
    }
}
