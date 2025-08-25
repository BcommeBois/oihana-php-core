<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

final class BetweenSpacesTest extends TestCase
{
    public function testStringWrapped(): void
    {
        $this->assertSame(' hello ', betweenSpaces('hello'));
    }

    public function testWrappingAndCompile(): void
    {
        $this->assertSame(' hello world ', betweenSpaces(['hello', 'world']));
    }

    public function testWrappingDisabled(): void
    {
        $this->assertSame('hello world', betweenSpaces(['hello', 'world'], false));
    }

    public function testEmptyExpression(): void
    {
        $this->assertSame('  ', betweenSpaces(''));
    }

    public function testNullExpression(): void
    {
        $this->assertSame('  ', betweenSpaces(null));
    }
}