<?php declare(strict_types=1);

namespace tests\oihana\core\strings;

use function oihana\core\strings\splitOutsideQuotes;

use PHPUnit\Framework\TestCase;

class SplitOutsideQuotesTest extends TestCase
{
    public function testSimpleSplit(): void
    {
        $this->assertSame(['a', 'b', 'c'], splitOutsideQuotes('a;b;c', ';'));
    }

    public function testEmptyInput(): void
    {
        $this->assertSame([''], splitOutsideQuotes('', ';'));
    }

    public function testEmptySeparatorIsNoop(): void
    {
        $this->assertSame(['a;b'], splitOutsideQuotes('a;b', ''));
    }

    public function testSingleSegment(): void
    {
        $this->assertSame(['lonely'], splitOutsideQuotes('lonely', ';'));
    }

    public function testSeparatorInsideDoubleQuotes(): void
    {
        $this->assertSame(['a', ' "b;c"', ' d'], splitOutsideQuotes('a; "b;c"; d', ';'));
    }

    public function testTrim(): void
    {
        $this->assertSame(['a', '"b;c"', 'd'], splitOutsideQuotes('a; "b;c"; d', ';', true));
    }

    public function testSeparatorInsideSingleQuotes(): void
    {
        $this->assertSame(["a", "'b;c'", 'd'], splitOutsideQuotes("a;'b;c';d", ';'));
    }

    public function testSeparatorInsideBackticks(): void
    {
        $this->assertSame(['a', '`b;c`', 'd'], splitOutsideQuotes('a;`b;c`;d', ';'));
    }

    public function testSeparatorInsideGuillemets(): void
    {
        $this->assertSame(['a«b;c»d', 'e'], splitOutsideQuotes('a«b;c»d;e', ';'));
    }

    public function testEscapeEnabledTreatsBackslashAsEscape(): void
    {
        // \" inside a quoted region does NOT terminate it — the whole string is one segment
        $this->assertSame(['"a\\";b"'], splitOutsideQuotes('"a\\";b"', ';'));
    }

    public function testEscapeDisabledTreatsBackslashLiterally(): void
    {
        // with escape disabled, the " after \ closes the region; the ; then splits
        $this->assertSame(['"a\\"', 'b"'], splitOutsideQuotes('"a\\";b"', ';', false, ''));
    }

    public function testUnclosedQuoteIsTolerated(): void
    {
        $this->assertSame(['"unclosed; rest'], splitOutsideQuotes('"unclosed; rest', ';'));
    }

    public function testMultiByteSeparator(): void
    {
        $this->assertSame(['a', 'b', 'c'], splitOutsideQuotes('a→b→c', '→'));
    }

    public function testNoopWhenSeparatorAbsent(): void
    {
        $this->assertSame(['no separators here'], splitOutsideQuotes('no separators here', ';'));
    }

    public function testConsecutiveSeparators(): void
    {
        $this->assertSame(['a', '', 'b'], splitOutsideQuotes('a;;b', ';'));
    }

    public function testTrailingSeparator(): void
    {
        $this->assertSame(['a', 'b', ''], splitOutsideQuotes('a;b;', ';'));
    }
}
