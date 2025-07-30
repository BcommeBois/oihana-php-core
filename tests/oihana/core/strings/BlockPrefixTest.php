<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class BlockPrefixTest extends TestCase
{
    public function testBlockPrefixWithString(): void
    {
        $input = "a\n\nb";
        $expected = "  // a\n  // \n  // b";
        $this->assertSame($expected, blockPrefix($input, '// ', 2));
    }

    public function testBlockPrefixWithArray(): void
    {
        $input = ['x', '', 'y'];
        $expected = "-->x|-->|-->y"; // Correction ici
        $this->assertSame($expected, blockPrefix($input, '-->', '', '|'));
    }

    public function testBlockPrefixWithoutIndent(): void
    {
        $expected = ">>foo\n>>bar";
        $this->assertSame($expected, blockPrefix("foo\nbar", '>>'));
    }

    public function testBlockPrefixWithZeroIndentInt(): void
    {
        $expected = "**1\n**2";
        $this->assertSame($expected, blockPrefix(['1', '2'], '**', 0));
    }

    public function testBlockPrefixWithEmptyString(): void
    {
        $this->assertSame('>>>', blockPrefix('', '>>>'));
    }

    public function testBlockPrefixWithEmptyArray(): void
    {
        $this->assertSame('', blockPrefix([], '>>>'));
    }
}