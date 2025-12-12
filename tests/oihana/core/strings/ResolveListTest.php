<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class ResolveListTest extends TestCase
{
    public function testNullInputReturnsDefault()
    {
        $this->assertSame('default', resolveList(null, ';', PHP_EOL, 'default'));
        $this->assertNull(resolveList(null));
    }

    public function testEmptyStringReturnsDefault()
    {
        $this->assertSame('fallback', resolveList('', ';', PHP_EOL, 'fallback'));
        $this->assertNull(resolveList(''));
    }

    public function testStringSplittingAndTrimming()
    {
        $input = ' a ; b ;  c  ';
        $expected = "a\nb\nc";
        $this->assertSame($expected, resolveList($input));

        $input = 'apple|banana|  orange  ';
        $expected = 'apple,banana,orange';
        $this->assertSame($expected, resolveList($input, '|', ','));
    }

    public function testStringWithOnlyEmptyPartsReturnsDefault()
    {
        $this->assertSame('empty', resolveList(';;;  ; ;', ';', PHP_EOL, 'empty'));
        $this->assertNull(resolveList(';;;'));
    }

    public function testArrayInput()
    {
        $input = ['foo', '  bar  ', '', 'baz', ' '];
        $expected = "foo\nbar\nbaz";
        $this->assertSame($expected, resolveList($input));

        $expected2 = 'foo,bar,baz';
        $this->assertSame($expected2, resolveList($input, ',', ','));
    }

    public function testArrayWithOnlyEmptyStringsReturnsDefault()
    {
        $input = ['', '   ', "\t"];
        $this->assertSame('N/A', resolveList($input, ';', PHP_EOL, 'N/A'));
        $this->assertNull(resolveList($input));
    }

    public function testCustomSeparators()
    {
        $input = 'one#two#three';
        $expected = "one|two|three";
        $this->assertSame($expected, resolveList($input, '#', '|'));
    }
}