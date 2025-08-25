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
        $this->assertSame("{foo:'bar'}", object("foo:'bar'"));
        $this->assertSame("{ foo:'bar' }", object("foo:'bar'", true));
        $this->assertSame("{foo:'bar',baz:42}", object("foo:'bar',baz:42"));
    }

    public function testArrayKeyValuePairs()
    {
        $input = [ ['name', "'Eka'"], ['age', 47] ];
        $this->assertSame("{name:'Eka',age:47}", object($input));
        $this->assertSame("{ name:'Eka', age:47 }", object($input, true));
    }

    public function testAssociativeArray()
    {
        $input = ['name' => "'Eka'", 'age' => 47];
        $this->assertSame("{name:'Eka',age:47}", object($input));
        $this->assertSame("{ name:'Eka', age:47 }", object($input, true));
    }

    public function testMixedArray()
    {
        $input = [
            ['name', "'Eka'"],
            "country:'FR'",
            'age' => 47,
            ['city', "'Paris'"]
        ];
        $expected = "{name:'Eka',country:'FR',age:47,city:'Paris'}";
        $expectedSpace = "{ name:'Eka', country:'FR', age:47, city:'Paris' }";

        $this->assertSame($expected, object($input));
        $this->assertSame($expectedSpace, object($input, true));
    }

    public function testValuesBoolNullArray()
    {
        $input = [
            'active' => true,
            'deleted' => false,
            'nickname' => null,
            'tags' => ['php','js']
        ];
        $expected = "{active:true,deleted:false,nickname:null,tags:[php, js]}";
        $expectedSpace = "{ active:true, deleted:false, nickname:null, tags:[php, js] }";

        $this->assertSame($expected, object($input));
        $this->assertSame($expectedSpace, object($input, true));
    }

    public function testInvalidArrayThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        object([123]); // neither [key,value] nor string nor associative
    }
}