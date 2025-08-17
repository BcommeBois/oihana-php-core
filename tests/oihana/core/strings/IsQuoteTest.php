<?php

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class IsQuoteTest extends TestCase
{
    public function testSingleQuotes(): void
    {
        $this->assertTrue(isQuote("'"));
        $this->assertTrue(isQuote('"'));
        $this->assertTrue(isQuote('`'));
    }

    public function testFrenchQuotes(): void
    {
        $this->assertTrue(isQuote('«'));
        $this->assertTrue(isQuote('»'));
    }

    public function testEnglishTypographicQuotes(): void
    {
        $this->assertTrue(isQuote('“'));
        $this->assertTrue(isQuote('”'));
        $this->assertTrue(isQuote('‘'));
        $this->assertTrue(isQuote('’'));
    }

    public function testNonQuoteCharacters(): void
    {
        $this->assertFalse(isQuote('a'));
        $this->assertFalse(isQuote('1'));
        $this->assertFalse(isQuote(' '));
        $this->assertFalse(isQuote('@'));
    }
}