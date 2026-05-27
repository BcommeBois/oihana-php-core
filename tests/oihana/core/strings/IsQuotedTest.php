<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class IsQuotedTest extends TestCase
{
    public function testEmptyIsFalse(): void
    {
        $this->assertFalse(isQuoted(''));
    }

    public function testSingleCharIsFalse(): void
    {
        $this->assertFalse(isQuoted('"'));
        $this->assertFalse(isQuoted("'"));
        $this->assertFalse(isQuoted('«'));
    }

    public function testDoubleQuotes(): void
    {
        $this->assertTrue(isQuoted('"hello"'));
        $this->assertTrue(isQuoted('""'));
    }

    public function testSingleQuotes(): void
    {
        $this->assertTrue(isQuoted("'hello'"));
    }

    public function testBackticks(): void
    {
        $this->assertTrue(isQuoted('`hello`'));
    }

    public function testFrenchGuillemets(): void
    {
        $this->assertTrue(isQuoted('«hello»'));
    }

    public function testEnglishTypographicQuotes(): void
    {
        $this->assertTrue(isQuoted('“hello”'));
        $this->assertTrue(isQuoted('‘hello’'));
    }

    public function testUnmatchedIsFalse(): void
    {
        $this->assertFalse(isQuoted('"hello'));
        $this->assertFalse(isQuoted('hello"'));
        $this->assertFalse(isQuoted('»hello«'));
        $this->assertFalse(isQuoted('"hello\''));
    }

    public function testUnquotedIsFalse(): void
    {
        $this->assertFalse(isQuoted('hello'));
    }
}
