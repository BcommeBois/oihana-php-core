<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class FormatQuotedStringTest extends TestCase
{
    public function testSimpleSingleQuote(): void
    {
        $this->assertSame("'Hello world'", formatQuotedString("Hello world"));
    }

    public function testSingleQuoteEscaping(): void
    {
        $this->assertSame("'She said: \\'hi\\''", formatQuotedString("She said: 'hi'"));
    }

    public function testDoubleQuoteStyle(): void
    {
        $this->assertSame('"Line\\nBreak"', formatQuotedString("Line\nBreak", 'double'));
    }

    public function testDoubleQuoteEscapingBackslash(): void
    {
        $this->assertSame('"Use \\\\ backslash"', formatQuotedString("Use \\ backslash", 'double'));
    }

    public function testSingleQuoteWithBackslash(): void
    {
        $this->assertSame("'Use \\\\ backslash'", formatQuotedString("Use \\ backslash"));
    }

    public function testCompactModeWithSingleQuotes(): void
    {
        $this->assertSame("'Tabbed\\ttext'", formatQuotedString("Tabbed\ttext", 'single', true));
    }

    public function testControlCharsWithoutCompact(): void
    {
        // In single quote, without compact, control characters not replaced
        $this->assertSame("'Tabbed\ttext'", formatQuotedString("Tabbed\ttext"));
    }

    public function testAllControlCharsInDouble(): void
    {
        $input = "\n\r\t\v\e\f";
        $expected = '"\\n\\r\\t\\v\\e\\f"';
        $this->assertSame($expected, formatQuotedString($input, 'double'));
    }

    public function testEmptyString(): void
    {
        $this->assertSame("''", formatQuotedString(''));
        $this->assertSame('""', formatQuotedString('', 'double'));
    }

    public function testInvalidQuoteStyleDefaultsToSingle(): void
    {
        // Any unknown quoteStyle should fallback to single (as per implementation)
        $this->assertSame("'value'", formatQuotedString("value", 'invalid-style'));
    }

}