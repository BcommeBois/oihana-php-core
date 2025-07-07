<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class HyphenateTest extends TestCase
{
    /**
     * Test basic camelCase conversion
     */
    public function testCamelCaseToHyphenated()
    {
        $this->assertSame('hello-world'        , hyphenate('helloWorld' ) ) ;
        $this->assertSame('foo-bar-baz'        , hyphenate('fooBarBaz' ) ) ;
        $this->assertSame('x-m-l-http-request' , hyphenate('XMLHttpRequest' ) ) ;
    }


    /**
     * Test already hyphenated string (should remain unchanged)
     */
    public function testAlreadyHyphenatedString()
    {
        $this->assertSame('already-hyphenated', hyphenate('already-hyphenated'));
    }

    /**
     * Test string with no uppercase letters
     */
    public function testLowercaseOnly()
    {
        $this->assertSame('simple', hyphenate('simple'));
    }

    /**
     * Test empty and null inputs
     */
    public function testEmptyAndNullInput()
    {
        $this->assertSame('', hyphenate(''));
        $this->assertSame('', hyphenate(null));
    }
}