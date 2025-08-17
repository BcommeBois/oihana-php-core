<?php

namespace oihana\core\strings ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ObjectTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyBraces()
    {
        $this->assertSame("{}", object());
        $this->assertSame("{}", object([], true));
    }

    public function testNullReturnsEmptyBraces()
    {
        $this->assertSame("{}", object(null));
        $this->assertSame("{}", object(null, true));
    }

    public function testStringInputIsWrapped()
    {
        $this->assertSame("{foo: 'bar'}", object("foo: 'bar'"));
        $this->assertSame("{ foo: 'bar' }", object("foo: 'bar'", true));
    }

    public function testArrayKeyValuePairs()
    {
        $input = [ ['name', "'Eka'"] , ['age', 47] ];
        $this->assertSame( "{name:'Eka',age:47}"      , object( $input ) );
        $this->assertSame( "{ name:'Eka', age:47 }" , object( $input , true ) ) ;
    }

    public function testInvalidArrayThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        object([['onlyOneValue']]);
    }
}