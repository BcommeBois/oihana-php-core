<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PadBothTest extends TestCase
{
    public function testPadBothBasic()
    {
        $this->assertSame('abahelloaba', padBoth('hello', 11, 'ab'));
        $this->assertSame('☺hello☺☺', padBoth('hello', 8, '☺'));
    }

    public function testPadBothNullSource()
    {
        $this->assertSame('******', padBoth(null, 6, '*'));
        $this->assertSame('', padBoth(null, 0, '*'));
    }

    public function testPadBothNoPaddingIfSizeLessOrEqual()
    {
        $this->assertSame('hello', padBoth('hello', 5, '*'));
        $this->assertSame('hello', padBoth('hello', 3, '*'));
    }

    public function testPadBothMultiBytePadding()
    {
        $this->assertSame('🌟hello🌟🌟', padBoth('hello', 8, '🌟'));
    }

    public function testPadBothPartialPad()
    {
        // Padding string longer than 1 grapheme, partial repeat expected on both sides
        $this->assertSame('abhelloaba', padBoth('hello', 10, 'ab'));
        $this->assertSame('abtestabc', padBoth('test', 9, 'abc'));
    }

    public function testPadBothThrowsExceptionOnEmptyPad()
    {
        $this->expectException(InvalidArgumentException::class);
        padBoth('hello', 10, '');
    }

    public function testPadBothThrowsExceptionOnInvalidUtf8Pad()
    {
        $this->expectException(InvalidArgumentException::class);
        padBoth('hello', 10, "\x80\x81");
    }
}