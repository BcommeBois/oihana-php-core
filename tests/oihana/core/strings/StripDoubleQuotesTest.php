<?php declare(strict_types=1);

namespace tests\oihana\core\strings;

use function oihana\core\strings\stripDoubleQuotes;

use PHPUnit\Framework\TestCase;

class StripDoubleQuotesTest extends TestCase
{
    public function testEmptyString(): void
    {
        $this->assertSame('', stripDoubleQuotes(''));
    }

    public function testWrappedValue(): void
    {
        $this->assertSame('hello', stripDoubleQuotes('"hello"'));
    }

    public function testTwoQuotesReturnsEmpty(): void
    {
        $this->assertSame('', stripDoubleQuotes('""'));
    }

    public function testSingleCharIsNoop(): void
    {
        $this->assertSame('"', stripDoubleQuotes('"'));
    }

    public function testUnmatchedLeftIsNoop(): void
    {
        $this->assertSame('"hello', stripDoubleQuotes('"hello'));
    }

    public function testUnmatchedRightIsNoop(): void
    {
        $this->assertSame('hello"', stripDoubleQuotes('hello"'));
    }

    public function testSingleQuotesAreNoop(): void
    {
        $this->assertSame("'hello'", stripDoubleQuotes("'hello'"));
    }

    public function testNoQuotesIsNoop(): void
    {
        $this->assertSame('hello', stripDoubleQuotes('hello'));
    }

    public function testOnlyOneLayerStripped(): void
    {
        $this->assertSame('"foo"', stripDoubleQuotes('""foo""'));
    }

    public function testQuotedPairEscapesNotDecoded(): void
    {
        // RFC 7230 quoted-pair escapes are NOT decoded by design.
        $this->assertSame('a\\"b', stripDoubleQuotes('"a\\"b"'));
    }

    public function testInnerQuotesPreserved(): void
    {
        $this->assertSame('a"b', stripDoubleQuotes('"a"b"'));
    }
}
