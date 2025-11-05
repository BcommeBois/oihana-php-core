<?php

namespace oihana\core ;

use PHPUnit\Framework\TestCase;

final class ToNumberTest extends TestCase
{
    public function testReturnsIntWhenValueIsIntegerString(): void
    {
        $this->assertSame(42, toNumber('42'));
    }

    public function testReturnsFloatWhenValueIsFloatString(): void
    {
        $this->assertSame(3.14, toNumber('3.14'));
    }

    public function testReturnsFloatWhenValueIsScientificNotation(): void
    {
        $this->assertSame(-50.0, toNumber('-0.5e2'));
    }

    public function testReturnsDefaultWhenValueIsNotNumeric(): void
    {
        $this->assertFalse(toNumber('foo'));
        $this->assertSame(0, toNumber('foo', 0));
        $this->assertSame(-1, toNumber('foo', -1));
    }

    public function testReturnsNumericTypesAsIs(): void
    {
        $this->assertSame(100, toNumber(100));
        $this->assertSame(5.5, toNumber(5.5));
    }

    public function testReturnsDefaultForNull(): void
    {
        $this->assertFalse(toNumber(null));
        $this->assertSame(-1, toNumber(null, -1));
    }
}