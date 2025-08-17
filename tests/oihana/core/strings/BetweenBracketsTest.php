<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

final class BetweenBracketsTest extends TestCase
{
    public function testStringWrapped(): void
    {
        $this->assertSame('[hello]', betweenBrackets('hello'));
        $this->assertSame('[123]', betweenBrackets('123'));
    }

    public function testArrayWrappedWithDefaultSeparator(): void
    {
        $this->assertSame('[a b c]', betweenBrackets(['a', 'b', 'c']));
        $this->assertSame('[1 2 3]', betweenBrackets([1, 2, 3]));
    }

    public function testArrayWrappedWithCustomSeparator(): void
    {
        $this->assertSame('[a,b,c]', betweenBrackets(['a', 'b', 'c'], true, ','));
        $this->assertSame('[x|y|z]', betweenBrackets(['x', 'y', 'z'], true, '|'));
    }

    public function testWrappingDisabled(): void
    {
        $this->assertSame('hello', betweenBrackets('hello', false));
        $this->assertSame('a b c', betweenBrackets(['a', 'b', 'c'], false));
    }

    public function testEmptyExpression(): void
    {
        $this->assertSame('[]', betweenBrackets(''));
        $this->assertSame('[]', betweenBrackets([]));
    }
}