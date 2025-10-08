<?php

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;
use Stringable;

final class CompileTest extends TestCase
{
    public function testCompileWithSimpleString(): void
    {
        $this->assertSame('hello', compile('hello') ) ;
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

        $this->assertSame('foo "bar"', compile(['foo', '', null, '"bar"']));
    }

    public function testCompileWithNestedArray(): void
    {
        $this->assertSame('a b c d', compile(['a', ['b', 'c'], 'd']));
    }

    public function testCompileWithNull(): void
    {
        $this->assertSame('', compile(null));
    }

    public function testCompileWithBool(): void
    {
        $this->assertSame('true', compile(true));
        $this->assertSame('false', compile(false));
    }

    public function testCompileWithStringable(): void
    {
        $obj = new class implements Stringable
        {
            public function __toString(): string { return 'stringable'; }
        };
        $this->assertSame('stringable', compile( $obj ) ) ;
    }

    public function testCompileWithObject(): void
    {
        $obj = (object)['foo' => 'bar'];
        $this->assertSame(json_encode($obj), compile($obj));
    }

    public function testCompileWithCallback(): void
    {
        $array = ['a', 'b', 'c'];
        $callback = fn($v) => strtoupper($v);
        $this->assertSame('A B C', compile($array, ' ', $callback));
    }

    public function testCompileWithCallbackAndNestedArray(): void
    {
        $array = [ 'a' , [ 'b' , 'c' ] ] ;
        $callback = function( $v ) use ( &$callback )
        {
            return is_array( $v ) ? compile( $v , ' ' , $callback ) : strtoupper($v);
        };
        $this->assertSame('A B C', compile( $array , ' ' , $callback ) );
    }
}