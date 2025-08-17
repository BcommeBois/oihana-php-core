<?php declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

final class BetweenBracesTest extends TestCase
{
    public function testStringWrapped(): void
    {
        $this->assertSame('{hello}', betweenBraces('hello'));
    }

    public function testArrayWrappedDefaultSeparator(): void
    {
        $this->assertSame('{a b c}', betweenBraces(['a', 'b', 'c']));
    }

    public function testArrayWrappedCustomSeparator(): void
    {
        $this->assertSame('{a,b,c}', betweenBraces(['a', 'b', 'c'], true, ','));
    }

    public function testWrappingDisabled(): void
    {
        $this->assertSame('x y', betweenBraces(['x', 'y'], false));
    }

    public function testEmptyExpression(): void
    {
        $this->assertSame('{}', betweenBraces(''));
    }

    // not supported yet
    // public function testNestedArrays(): void
    // {
    //     $nested = ['x', ['y', 'z'], 'w'];
    //     $this->assertSame('{x y z w}', betweenBraces($nested));
    // }

    public function testStringable(): void
    {
        $strObj = new class implements \Stringable {
            public function __toString(): string { return 'foo'; }
        };
        $this->assertSame('{foo}', betweenBraces($strObj));
    }

    public function testNullExpression(): void
    {
        $this->assertSame('{}', betweenBraces(null));
    }
}