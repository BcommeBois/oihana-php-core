<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PadEndTest extends TestCase
{
    public function testPadEndRightPadding()
    {
        $this->assertSame('hello☺☺☺☺☺', padEnd('hello', 10, '☺'));
        $this->assertSame('test*****', padEnd('test', 9, '*'));
    }

    public function testPadEndNoPaddingIfSizeLessOrEqual()
    {
        $this->assertSame('hello', padEnd('hello', 5, '*'));
        $this->assertSame('hello', padEnd('hello', 3, '*'));
    }

    public function testPadEndNullSource()
    {
        $this->assertSame('*****', padEnd(null, 5, '*'));
        $this->assertSame('', padEnd(null, 0, '*'));
    }

    public function testPadEndMultiBytePadding()
    {
        $this->assertSame('hello🌟🌟', padEnd('hello', 7, '🌟'));
        $this->assertSame('abc☺☺☺', padEnd('abc', 6, '☺'));
    }

    public function testPadEndPartialPad()
    {
        // padding string longer than 1 grapheme, partial repeat expected
        $this->assertSame('abcab', padEnd('abc', 5, 'ab'));
        $this->assertSame('testabcab', padEnd('test', 9, 'abc'));
    }

    public function testPadEndThrowsExceptionOnEmptyPad()
    {
        $this->expectException(InvalidArgumentException::class);
        padEnd('hello', 10, '');
    }

    public function testPadEndThrowsExceptionOnInvalidUtf8Pad()
    {
        $this->expectException(InvalidArgumentException::class);
        // invalid UTF-8 byte sequence
        padEnd('hello', 10, "\x80\x81");
    }
}