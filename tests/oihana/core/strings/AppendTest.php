<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AppendTest extends TestCase
{
    public function testAppendWithNullSource()
    {
        $this->assertSame('foo', append(null, 'foo'));
        $this->assertSame('', append(null));
    }

    public function testAppendWithSingleSuffix()
    {
        $this->assertSame('hellofoo', append('hello', 'foo'));
    }

    public function testAppendWithMultipleSuffixes()
    {
        $this->assertSame('hello world!', append('hello', ' ', 'world', '!'));
    }

    public function testReturnsNormalizedString()
    {
        // Compose a string that is not normalized NFC (é decomposé en e + accent)
        $notNormalized = "e\xCC\x81"; // e + ´ (combining acute accent)
        $result = append($notNormalized, ' suffix');
        // The normalized form of "é" (NFC) is \xC3\xA9
        $this->assertStringContainsString('é', $result);
        $this->assertTrue(normalizer_is_normalized($result));
    }

    public function testThrowsExceptionOnInvalidUtf8()
    {
        $this->expectException( InvalidArgumentException::class );

        // Create an invalid UTF-8 string (invalid byte sequence)
        $invalidUtf8 = "hello\xFF";

        // This should throw because normalization fails
        append($invalidUtf8, 'suffix');
    }
}