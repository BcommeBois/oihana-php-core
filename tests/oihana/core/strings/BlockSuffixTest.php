<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class BlockSuffixTest extends TestCase
{
    public function testBlockSuffixWithString(): void
    {
        $input = "a\n\nb";
        $expected = "  a //\n   //\n  b //";
        $this->assertSame($expected, blockSuffix($input, ' //', 2));
    }

    public function testBlockSuffixWithArray(): void
    {
        $input = ['a', '', 'b'];
        $expected = "a <-| <-|b <-";
        $this->assertSame($expected, blockSuffix($input, ' <-', '', '|'));
    }

    public function testBlockSuffixWithZeroIndent(): void
    {
        $expected = "1**\n2**";
        $this->assertSame($expected, blockSuffix(['1', '2'], '**', 0));
    }

    public function testBlockSuffixWithEmptyString(): void
    {
        $this->assertSame('<<<', blockSuffix('', '<<<'));
    }

    public function testBlockSuffixWithEmptyArray(): void
    {
        $this->assertSame('', blockSuffix([], '<<<'));
    }
}