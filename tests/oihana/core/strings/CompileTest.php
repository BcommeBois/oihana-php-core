<?php

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;
use Stringable;

class CompileTest extends TestCase
{
    public function testCompileWithSimpleString(): void
    {
        $this->assertSame('hello', compile('hello'));
    }

    public function testCompileWithArray(): void
    {
        $this->assertSame('foo bar', compile(['foo', 'bar']));
    }

    public function testCompileWithArrayAndSeparator(): void
    {
        $this->assertSame('a, b, c', compile(['a', 'b', 'c'], ', '));
    }

    public function testCompileWithEmptyArray(): void
    {
        $this->assertSame('', compile([]));
    }

    public function testCompileWithArrayContainingEmptyValues(): void
    {
        $this->assertSame('foo bar', compile(['foo', '', null, 'bar']));
    }

    public function testCompileWithNull(): void
    {
        $this->assertSame('', compile(null));
    }

    public function testCompileWithStringable(): void
    {
        $obj = new class implements Stringable {
            public function __toString(): string { return 'stringable'; }
        };

        $this->assertSame('stringable', compile($obj));
    }
}