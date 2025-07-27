<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use PHPUnit\Framework\TestCase;

final class GetKeyValueTest extends TestCase
{
    public function testGetValueFromArray(): void
    {
        $doc = ['name' => 'Alice', 'age' => 30];
        $this->assertSame('Alice', getKeyValue($doc, 'name'));
        $this->assertSame(30, getKeyValue($doc, 'age'));
        $this->assertNull(getKeyValue($doc, 'email'));
    }

    public function testGetValueFromArrayForced(): void
    {
        $doc = ['x' => 42];
        $this->assertSame(42, getKeyValue($doc, 'x', true));
    }

    public function testGetValueFromObject(): void
    {
        $doc = (object)['name' => 'Bob', 'age' => 25];
        $this->assertSame('Bob', getKeyValue($doc, 'name'));
        $this->assertSame(25, getKeyValue($doc, 'age'));
        $this->assertNull(getKeyValue($doc, 'email'));
    }

    public function testGetValueFromObjectForced(): void
    {
        $doc = (object)['x' => 99];
        $this->assertSame(99, getKeyValue($doc, 'x', false));
    }

    public function testNullValueAndMissingKey(): void
    {
        $docArray  = ['a' => null];
        $docObject = (object)['a' => null];

        $this->assertNull(getKeyValue($docArray, 'a'));
        $this->assertNull(getKeyValue($docObject, 'a'));
        $this->assertNull(getKeyValue($docArray, 'b'));
        $this->assertNull(getKeyValue($docObject, 'b'));
    }

    public function testExceptionOnArrayExpectedButObjectGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type override');

        $doc = (object)['foo' => 'bar'];
        getKeyValue($doc, 'foo', true); // Wrong forced type
    }

    public function testExceptionOnObjectExpectedButArrayGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type override');

        $doc = ['foo' => 'bar'];
        getKeyValue($doc, 'foo', false); // Wrong forced type
    }
}