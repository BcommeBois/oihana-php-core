<?php

namespace oihana\core\strings ;

use Normalizer;
use PHPUnit\Framework\TestCase;

final class SanitizeTest extends TestCase
{
    public function testTrimDefault()
    {
        $this->assertSame(
            'Hello World',
            sanitize("\n\t Hello World \n")
        );

        $this->assertSame(
            "Hello\nWorld",
            sanitize("\n\t Hello\n World \n")
        );
    }

    public function testNullifyFlag()
    {
        $this->assertNull
        (
            sanitize("\n\t  ", SanitizeFlag::TRIM | SanitizeFlag::NULLIFY )
        );

        $this->assertSame( '' , sanitize("\n\t  " ) ) ;
    }

    public function testRemoveInvisible()
    {
        $this->assertSame(
            'HelloWorld',
            sanitize("Hello\u{200B}World", SanitizeFlag::REMOVE_INVISIBLE)
        );

        $this->assertSame(
            'Hello World',
            sanitize("Hello World", SanitizeFlag::REMOVE_INVISIBLE)
        );
    }

    public function testNormalizeLineBreaks()
    {
        $text = "Line1\r\nLine2\rLine3";
        $expected = "Line1\nLine2\nLine3";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::NORMALIZE_LINE_BREAKS)
        );
    }

    public function testRemoveExtraLineBreaks()
    {
        $text = "Line1\n\n\nLine2\n\nLine3";
        $expected = "Line1\nLine2\nLine3";

        $flags = SanitizeFlag::REMOVE_EXTRA_LINE_BREAKS | SanitizeFlag::NORMALIZE_LINE_BREAKS;
        $this->assertSame(
            $expected,
            sanitize($text, $flags)
        );
    }

    public function testCombineFlags()
    {
        $text = "\r\n  Hello \u{200B}World \r\n\n";
        $flags = SanitizeFlag::TRIM
            | SanitizeFlag::REMOVE_INVISIBLE
            | SanitizeFlag::NORMALIZE_LINE_BREAKS
            | SanitizeFlag::REMOVE_EXTRA_LINE_BREAKS;

        $expected = "Hello World";
        $this->assertSame(
            $expected,
            sanitize($text, $flags)
        );
    }

    public function testNullInput()
    {
        $this->assertSame(
            '',
            sanitize(null)
        );

        $this->assertNull(
            sanitize(null, SanitizeFlag::NULLIFY)
        );
    }

    public function testEmptyString()
    {
        $this->assertSame(
            '',
            sanitize('')
        );

        $this->assertNull(
            sanitize('', SanitizeFlag::NULLIFY)
        );
    }

    public function testInvisibleCharactersComprehensive()
    {
        // Zero-width characters
        $this->assertSame(
            'HelloWorld',
            sanitize("Hello\u{200B}\u{200C}\u{200D}World", SanitizeFlag::REMOVE_INVISIBLE)
        );

        // BOM (Byte Order Mark)
        $this->assertSame(
            'Hello',
            sanitize("\u{FEFF}Hello", SanitizeFlag::REMOVE_INVISIBLE)
        );

        // Control characters (keeping line breaks)
        $this->assertSame(
            "Hello\nWorld",
            sanitize("Hello\x00\nWorld", SanitizeFlag::REMOVE_INVISIBLE)
        );
    }

    public function testProcessingOrder()
    {
        // Test that trim happens AFTER normalization
        $text = "\r\nHello\r\n";
        $expected = "Hello";

        $flags = SanitizeFlag::TRIM | SanitizeFlag::NORMALIZE_LINE_BREAKS;
        $this->assertSame($expected, sanitize($text, $flags));
    }

    public function testInvalidFlags()
    {
        $this->expectException(\InvalidArgumentException::class);
        sanitize("test", 99999);
    }

    public function testAllFlags()
    {
        $text = "  \u{FEFF}Hello\r\n\r\n\r\nWorld\u{200B}  ";
        $expected = "Hello\nWorld";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::ALL)
        );
    }

    public function testEmptyAfterProcessing()
    {
        // String that becomes empty after removing invisible chars
        $this->assertNull(
            sanitize("\u{200B}\u{200C}", SanitizeFlag::REMOVE_INVISIBLE | SanitizeFlag::NULLIFY)
        );
    }

    // ===== COLLAPSE_SPACES tests =====

    public function testCollapseSpaces()
    {
        $text = "Hello    World  !";
        $expected = "Hello World !";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::COLLAPSE_SPACES)
        );
    }

    public function testCollapseSpacesWithTabs()
    {
        $text = "Hello\t\t\tWorld";
        $expected = "Hello World";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::COLLAPSE_SPACES)
        );
    }

    public function testCollapseSpacesPreservesLineBreaks()
    {
        $text = "Line1  \n  Line2";
        $expected = "Line1 \n Line2";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::COLLAPSE_SPACES)
        );
    }

    // ===== STRIP_TAGS tests =====

    public function testStripTags()
    {
        $html = "<p>Hello <b>World</b>!</p>";
        $expected = "Hello World!";

        $this->assertSame(
            $expected,
            sanitize($html, SanitizeFlag::STRIP_TAGS)
        );
    }

    public function testStripTagsWithAllowedTags()
    {
        $html = "<p>Hello <b>World</b>!</p>";
        $expected = "Hello <b>World</b>!"; // Le <p> est retiré

        $this->assertSame(
            $expected,
            sanitize($html, SanitizeFlag::STRIP_TAGS, ['allowed_tags' => '<b>'])
        );
    }

    public function testStripTagsWithScript()
    {
        $html = "<script>alert('XSS')</script>Hello";
        $expected = "Hello";

        $this->assertSame(
            $expected,
            sanitize($html, SanitizeFlag::STRIP_TAGS)
        );
    }

    // ===== DECODE_ENTITIES tests =====

    public function testDecodeEntities()
    {
        $text = "Hello &amp; goodbye &lt;tag&gt;";
        $expected = "Hello & goodbye <tag>";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::DECODE_ENTITIES)
        );
    }

    public function testDecodeEntitiesWithQuotes()
    {
        $text = "&quot;Hello&quot;";
        $expected = '"Hello"';

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::DECODE_ENTITIES)
        );
    }

    // ===== REMOVE_CONTROL_CHARS tests =====

    public function testRemoveControlChars()
    {
        $text = "Hello\x00\x01World";
        $expected = "HelloWorld";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::REMOVE_CONTROL_CHARS)
        );
    }

    public function testRemoveControlCharsKeepsLineBreaks()
    {
        $text = "Hello\n\tWorld";
        $expected = "Hello\n\tWorld";

        $this->assertSame(
            $expected,
            sanitize($text, SanitizeFlag::REMOVE_CONTROL_CHARS)
        );
    }

    // ===== NORMALIZE_UNICODE tests =====

    public function testNormalizeUnicode()
    {
        // é can be represented as single char (U+00E9) or e + combining accent (U+0065 U+0301)
        $text = "café"; // Using decomposed form

        $result = sanitize($text, SanitizeFlag::NORMALIZE_UNICODE);

        // Should normalize to composed form (NFC)
        $this->assertSame(
            Normalizer::normalize($text, Normalizer::NFC),
            $result
        );
    }

    // ===== Combined flags tests =====

    public function testCleanHtmlPreset()
    {
        $html = "<p>Hello   &amp;   <b>World</b>!</p>";
        $expected = "Hello & World!";

        $this->assertSame(
            $expected,
            sanitize($html, SanitizeFlag::CLEAN_HTML)
        );
    }

    public function testStrictPreset()
    {
        $text = "  Hello\x00\n\n\nWorld\u{200B}  ";
        $expected = "Hello\nWorld";

        $result = sanitize($text, SanitizeFlag::STRICT);

        $this->assertSame($expected, $result);
    }

    public function testStrictPresetReturnsNull()
    {
        $text = "  \x00\u{200B}  ";

        $this->assertNull(
            sanitize($text, SanitizeFlag::STRICT)
        );
    }

    // ===== Complex scenarios =====

    public function testStripTagsRemovesScriptContent()
    {
        $html = "<script>alert('xss')</script>Hello";
        $this->assertSame(
            "Hello",  // Pas "alert('xss')Hello"
            sanitize($html, SanitizeFlag::STRIP_TAGS)
        );
    }

    public function testComplexUserInput()
    {
        $input = "  <script>alert('xss')</script>Hello&nbsp;&nbsp;World\r\n\r\nTest\u{200B}  ";

        $flags = SanitizeFlag::TRIM
            | SanitizeFlag::STRIP_TAGS
            | SanitizeFlag::DECODE_ENTITIES
            | SanitizeFlag::REMOVE_INVISIBLE
            | SanitizeFlag::NORMALIZE_LINE_BREAKS
            | SanitizeFlag::REMOVE_EXTRA_LINE_BREAKS
            | SanitizeFlag::COLLAPSE_SPACES;

        // &nbsp; devient espace, puis collapse en un seul espace
        $expected = "Hello World\nTest";

        $this->assertSame($expected, sanitize($input, $flags));
    }
}