<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PadStartTest extends TestCase
{
    public function testPadStartWithShorterString()
    {
        $result = padStart('hello', 10, ' ');
        $this->assertSame('     hello', $result);
    }

    public function testPadStartWithExactLengthString()
    {
        $result = padStart('hello', 5, ' ');
        $this->assertSame('hello', $result);
    }

    public function testPadStartWithLongerString()
    {
        $result = padStart('hello world', 5, ' ');
        $this->assertSame('hello world', $result);
    }

    public function testPadStartWithMultiBytePad()
    {
        $result = padStart('test', 7, '☺');
        $this->assertSame('☺☺☺test', $result);
    }

    public function testPadStartWithNullSource()
    {
        $result = padStart(null, 4, '*');
        $this->assertSame('****', $result);
    }

    public function testPadStartWithEmptyPadStringThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        padStart('test', 5, '');
    }

    public function testPadStartWithInvalidUtf8PadThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        // Invalid UTF-8 sequence
        padStart('test', 5, "\xFF");
    }
}