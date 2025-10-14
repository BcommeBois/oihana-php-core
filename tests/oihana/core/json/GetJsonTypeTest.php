<?php

namespace oihana\core\json;

use PHPUnit\Framework\TestCase;

final class GetJsonTypeTest extends TestCase
{
    public function testNull(): void
    {
        $this->assertSame('null', getJsonType(null));
    }

    public function testBoolean(): void
    {
        $this->assertSame('boolean', getJsonType(true));
        $this->assertSame('boolean', getJsonType(false));
    }

    public function testInteger(): void
    {
        $this->assertSame('integer', getJsonType(0));
        $this->assertSame('integer', getJsonType(42));
        $this->assertSame('integer', getJsonType(-100));
    }

    public function testFloat(): void
    {
        $this->assertSame('number', getJsonType(3.14));
        $this->assertSame('number', getJsonType(-0.01));
        $this->assertSame('number', getJsonType(0.0));
    }

    public function testString(): void
    {
        $this->assertSame('string', getJsonType(''));
        $this->assertSame('string', getJsonType('hello'));
    }

    public function testArray(): void
    {
        $this->assertSame('array', getJsonType([]));
        $this->assertSame('array', getJsonType([1, 2, 3]));
        $this->assertSame('array', getJsonType(['a' => 1]));
    }

    public function testObject(): void
    {
        $this->assertSame('object', getJsonType(new \stdClass()));

        $obj = new class { public int $x = 1; };
        $this->assertSame('object', getJsonType($obj));
    }

    public function testDefaultValue(): void
    {
        $resource = fopen('php://memory', 'r');
        $this->assertSame('unknown', getJsonType($resource));
        fclose($resource);

        // Custom default
        $this->assertSame('other', getJsonType(tmpfile(), 'other'));
    }
}