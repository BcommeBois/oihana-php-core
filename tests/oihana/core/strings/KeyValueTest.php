<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;
use Stringable;

final class KeyValueTest extends TestCase
{
    public function testStringAndNumberValues()
    {
        $this->assertSame('name:Eka', keyValue('name', 'Eka'));
        $this->assertSame('age:30', keyValue('age', 30));
        $this->assertSame('price:99.99', keyValue('price', 99.99));
    }

    public function testBooleanValues()
    {
        $this->assertSame('active:true', keyValue('active', true));
        $this->assertSame('active:false', keyValue('active', false));
    }

    public function testNullValue()
    {
        $this->assertSame('description:null', keyValue('description', null));
    }

    public function testArrayValue()
    {
        $this->assertSame('tags:[php, js]', keyValue('tags', ['php','js']));
        $this->assertSame('empty:[]', keyValue('empty', []));
    }

    public function testStringableObject()
    {
        $obj = new class implements Stringable
        {
            public function __toString(): string
            {
                return 'stringable';
            }
        };

        $this->assertSame('object:stringable', keyValue('object', $obj));
    }

    public function testCustomSeparator()
    {
        $this->assertSame('key = value', keyValue('key', 'value', ' = '));
        $this->assertSame('foo->bar', keyValue('foo', 'bar', '->'));
    }

}