<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PrependTest extends TestCase
{
    public function testPrependSinglePrefix()
    {
        $this->assertSame('fooBar', prepend('Bar', 'foo'));
        $this->assertSame('foo', prepend(null, 'foo'));
        $this->assertSame('foo', prepend('', 'foo'));
    }

    public function testPrependMultiplePrefixes()
    {
        $this->assertSame('hello world', prepend('world', 'hello', ' '));
        $this->assertSame('abc123', prepend('123', 'a', 'b', 'c'));
    }

    public function testPrependEmptyPrefix()
    {
        $this->assertSame('test', prepend('test'));
        $this->assertSame('test', prepend('test', ''));
        $this->assertSame('', prepend(null));
    }

    public function testPrependNormalized()
    {
        $str = "e\u{301}"; // e + combining acute accent
        $normalized = "\u{e9}"; // é as single composed character

        $result = prepend($str, 'a');
        $this->assertTrue(normalizer_is_normalized($result));
        $this->assertStringStartsWith('a', $result);
    }

    public function testPrependInvalidUtf8()
    {
        $this->expectException(InvalidArgumentException::class);

        // Construct an invalid UTF-8 sequence manually
        $invalid = "\xC3\x28";
        prepend('foo', $invalid);
    }
}