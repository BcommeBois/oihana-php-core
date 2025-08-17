<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

class BetweenQuotesTest extends TestCase
{
    public function testStringDefault(): void
    {
        $this->assertSame("'hello'", betweenQuotes('hello'));
    }

    public function testStringCustomChar(): void
    {
        $this->assertSame('`hello`', betweenQuotes('hello', '`'));
    }

    public function testStringNoQuotes(): void
    {
        $this->assertSame('hello', betweenQuotes('hello', "'", false));
    }

    public function testIntegerAndFloat(): void
    {
        $this->assertSame("'123'", betweenQuotes(123));
        $this->assertSame("'45.67'", betweenQuotes(45.67));
    }

    public function testBoolean(): void
    {
        $this->assertSame("'1'", betweenQuotes(true));
        $this->assertSame("''", betweenQuotes(false));
    }

    public function testNull(): void
    {
        $this->assertSame("''", betweenQuotes(null));
    }

    public function testArrayDefaultSeparator(): void
    {
        $this->assertSame("'foo bar'", betweenQuotes(['foo', 'bar']));
    }

    public function testArrayCustomSeparator(): void
    {
        $this->assertSame("'foo,bar'", betweenQuotes(['foo', 'bar'], "'", true, ','));
    }

    public function testArrayNoQuotes(): void
    {
        $this->assertSame('foo bar', betweenQuotes(['foo', 'bar'], "'", false));
    }

    public function testObjectWithToString(): void
    {
        $obj = new class {
            public function __toString(): string
            {
                return 'object';
            }
        };
        $this->assertSame("'object'", betweenQuotes($obj));
    }

    public function testObjectWithoutToString(): void
    {
        $obj = new class { public function __toString() { return '' ;}}; // don't forget the __toString method
        $this->assertSame("''", betweenQuotes( $obj ) );
    }
}
