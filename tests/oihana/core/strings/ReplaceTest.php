<?php

namespace tests\oihana\core\strings;

use function oihana\core\strings\replace;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ReplaceTest extends TestCase
{
    // ------------------------------
    // Non-UTF8 mode (faster, ASCII-safe)
    // ------------------------------

    public function testBasicReplacementNonUtf8(): void
    {
        $this->assertSame(
            'Hello PHP',
            replace('Hello World', 'World', 'PHP', false, false)
        );
    }

    public function testCaseInsensitiveReplacementNonUtf8(): void
    {
        $this->assertSame(
            'Hello PHP',
            replace('Hello World', 'world', 'PHP', true, false)
        );
    }

    public function testEmptySourceOrFromNonUtf8(): void
    {
        // Null source returns empty string
        $this->assertSame('', replace(null, 'foo', 'bar', false, false));

        // Empty 'from' string returns original source
        $this->assertSame('Hello', replace('Hello', '', 'X', false, false));
    }

    public function testMultipleOccurrencesNonUtf8(): void
    {
        $source   = 'foo bar foo bar foo';
        $from     = 'foo';
        $to       = 'baz';

        $expected = 'baz bar baz bar baz';

        $this->assertSame(
            $expected,
            replace($source, $from, $to, false, false)
        );
    }

    // ------------------------------
    // UTF-8 mode with normalization
    // ------------------------------

    public function testUtf8Normalization(): void
    {
        // "e" + combining acute accent -> "é"
        $original = "caf\u{0065}\u{0301}";
        $expected = 'cafe';

        $this->assertSame(
            $expected,
            replace($original, 'é', 'e', false, true)
        );
    }

    public function testUtf8CaseInsensitive(): void
    {
        $this->assertSame(
            'Hello PHP',
            replace('Hello World', 'world', 'PHP', true, true)
        );
    }

    public function testThrowsOnInvalidUtf8(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // Inject invalid UTF-8 sequence
        replace("\xFF", "\xFF", 'a', false, true);
    }

    public function testMultipleOccurrencesUtf8(): void
    {
        $source   = 'I ❤️ PHP ❤️ PHP ❤️';
        $from     = '❤️';
        $to       = '💛';

        $expected = 'I 💛 PHP 💛 PHP 💛';

        $this->assertSame(
            $expected,
            replace($source, $from, $to, false, true)
        );
    }

    public function testUtf8EmptyFromReturnsSource(): void
    {
        // In UTF-8 mode an empty $from short-circuits and returns the source.
        $this->assertSame('abc', replace('abc', '', 'x', false, true));
    }

    public function testUtf8DenormalizedFromIsNormalized(): void
    {
        // Both source and $from are NFD-decomposed; replace() normalizes them.
        $source = "caf" . "e\u{0301}" ; // café (decomposed)
        $from   = "e\u{0301}" ;          // é   (decomposed)
        $this->assertSame('cafX', replace($source, $from, 'X', false, true));
    }

    public function testUtf8DenormalizedResultIsNormalized(): void
    {
        // A decomposed $to makes the raw result non-NFC, which replace() re-normalizes.
        $source = "caf" . "e\u{0301}" ;
        $from   = "e\u{0301}" ;
        $to     = "e\u{0301}" ;
        $this->assertSame("caf" . "é", replace($source, $from, $to, false, true));
    }
}