<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class BetweenDoubleQuotesTest extends TestCase
{
    public function testWrapStringDefault(): void
    {
        $this->assertSame('"hello"', betweenDoubleQuotes('hello'));
        $this->assertSame('"world"', betweenDoubleQuotes('world'));
    }

    public function testWrapArrayDefault(): void
    {
        $this->assertSame('"foo bar"', betweenDoubleQuotes(['foo', 'bar']));
        $this->assertSame('"one two three"', betweenDoubleQuotes(['one', 'two', 'three']));
    }

    public function testDisableWrapping(): void
    {
        $this->assertSame('hello', betweenDoubleQuotes('hello', '"', false));
        $this->assertSame('foo bar', betweenDoubleQuotes(['foo', 'bar'], '"', false));
    }

    public function testCustomSeparator(): void
    {
        $this->assertSame('"foo,bar,baz"', betweenDoubleQuotes(['foo', 'bar', 'baz'], '"', true, ','));
        $this->assertSame('"a|b|c"', betweenDoubleQuotes(['a','b','c'], '"', true, '|'));
    }

    public function testCustomWrapperCharacter(): void
    {
        $this->assertSame('`hello`', betweenDoubleQuotes('hello', '`'));
        $this->assertSame('`foo bar`', betweenDoubleQuotes(['foo','bar'], '`'));
    }

    public function testEmptyValues(): void
    {
        $this->assertSame('""', betweenDoubleQuotes(''));
        $this->assertSame('""', betweenDoubleQuotes([]));
    }

    public function testObjectsConvertibleToString(): void
    {
        $obj = new class {
            public function __toString(): string {
                return 'objectString';
            }
        };

        $this->assertSame('"objectString"', betweenDoubleQuotes($obj));
    }
}
