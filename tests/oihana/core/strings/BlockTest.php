<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    public function testEmptyLinesReturnsEmptyString(): void
    {
        $this->assertSame('', block([]));
    }

    public function testNoIndentDefaultsToSimpleJoin(): void
    {
        $this->assertSame
        (
            "a\nb\nc",
            block(['a', 'b', 'c'], '')
        );
    }

    public function testNumericIndentAppliesSpaces(): void
    {
        $this->assertSame
        (
            "    a\n    b\n    c",
            block(['a', 'b', 'c'], 4)
        );
    }

    public function testStringIndentIsApplied(): void
    {
        $this->assertSame
        (
            "\tline1\n\tline2",
            block(['line1', 'line2'], "\t")
        );
    }

    public function testCustomSeparatorIsUsed(): void
    {
        $this->assertSame
        (
            "x--y--z",
            block(['x', 'y', 'z'], '', '--')
        );
    }

    public function testCombinedIndentAndSeparator(): void
    {
        $this->assertSame
        (
            ">>one<<>>two",
            block(['one', 'two'], '>>', '<<')
        );
    }

    public function testZeroIndentAsIntProducesNoIndentation(): void
    {
        $this->assertSame
        (
            "a\nb",
            block(['a', 'b'], 0)
        );
    }

    public function testArrayWithNoIndent(): void
    {
        $this->assertSame("one\ntwo", block(['one', 'two']));
    }

    public function testArrayWithStringIndent(): void
    {
        $this->assertSame("--one\n--two", block(['one', 'two'], '--'));
    }

    public function testArrayWithIntegerIndent(): void
    {
        $this->assertSame("  one\n  two", block(['one', 'two'], 2));
    }

    public function testCustomSeparator(): void
    {
        $this->assertSame("one|two", block(['one', 'two'], '', '|'));
    }

    public function testStringInputWithDefaultOptions(): void
    {
        $this->assertSame("a\nb", block("a\nb"));
    }

    public function testStringInputWithIndent(): void
    {
        $this->assertSame(">>>a\n>>>b", block("a\nb", '>>>'));
    }

    public function testStringInputWithLineBreaksAndKeepEmptyLines(): void
    {
        $input = "a\n\nb";
        $expected = "·a\n·\n·b";
        $this->assertSame($expected, block($input, '·', PHP_EOL, true));
    }

    public function testStringInputWithLineBreaksAndSkipEmptyLines(): void
    {
        $input = "a\n\nb";
        $expected = "·a\n·b";
        $this->assertSame($expected, block($input, '·', PHP_EOL, false));
    }

    public function testEmptyArray(): void
    {
        $this->assertSame('', block([], '>>>'));
    }

    public function testEmptyString(): void
    {
        $this->assertSame('', block('', '>>>'));
    }

    public function testZeroIndentAsInt(): void
    {
        $this->assertSame("x\ny", block(['x', 'y'], 0));
    }

    public function testArrayInputWithEmptyLines(): void
    {
        $input = ['a', '', 'b'];

        $expectedWith = "·a\n·\n·b";
        $this->assertSame($expectedWith, block($input, '·', PHP_EOL, true));

        $expectedWithout = "·a\n·b";
        $this->assertSame($expectedWithout, block($input, '·', PHP_EOL, false));
    }
}