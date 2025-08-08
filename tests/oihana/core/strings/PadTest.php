<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PadTest extends TestCase
{
    public function testPadRightWithSpaces()
    {
        $result = pad('hello', 10, ' ', STR_PAD_RIGHT);
        $this->assertSame('hello     ', $result);
    }

    public function testPadLeftWithEmoji()
    {
        $result = pad('hello', 10, '☺', STR_PAD_LEFT);
        $this->assertSame('☺☺☺☺☺hello', $result);
    }

    public function testPadBothWithMultiCharPad()
    {
        $result = pad('hello', 10, 'ab', STR_PAD_BOTH);
        $this->assertSame('abhelloaba', $result);
    }

    public function testPadNullSourceReturnsEmptyString()
    {
        $result = pad(null, 5, '*', STR_PAD_RIGHT);
        $this->assertSame('*****', pad(null, 5, '*', STR_PAD_RIGHT));
    }

    public function testPadSizeLessThanLengthReturnsOriginal()
    {
        $result = pad('hello', 3, '*', STR_PAD_RIGHT);
        $this->assertSame('hello', $result);
    }

    public function testPadWithEmojiPad()
    {
        $result = pad('hi', 5, '👾', STR_PAD_RIGHT);
        $this->assertSame('hi👾👾👾', $result);
    }

    public function testPadWithPartialPad()
    {
        // pad length 2, padding 5 chars => 3 full pads + partial (1 char)
        $result = pad('abc', 8, 'ab', STR_PAD_RIGHT);
        $this->assertSame('abcababa', $result);

        // partial cut of 'ab' to 'a' at end
        $result2 = pad('abc', 9, 'ab', STR_PAD_RIGHT);
        $this->assertSame('abcababab', $result2);
    }

    public function testPadInvalidTypeThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        pad('hello', 10, ' ', 999);
    }
}