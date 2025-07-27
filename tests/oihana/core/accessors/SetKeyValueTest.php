<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use PHPUnit\Framework\TestCase;

final class SetKeyValueTest extends TestCase
{
    public function testSetValueInArray(): void
    {
        $doc = ['name' => 'Alice', 'age' => 30];
        $updated = setKeyValue($doc, 'name', 'Bob');
        $this->assertSame('Bob', $updated['name']);

        $updated = setKeyValue($doc, 'email', 'alice@example.com');
        $this->assertSame('alice@example.com', $updated['email']);
    }

    public function testSetValueInArrayForced(): void
    {
        $doc = ['x' => 42];
        $updated = setKeyValue($doc, 'x', 100, true);
        $this->assertSame(100, $updated['x']);
    }

    public function testSetValueInObject(): void
    {
        $doc = (object)['name' => 'Bob', 'age' => 25];
        $updated = setKeyValue($doc, 'name', 'Alice');
        $this->assertSame('Alice', $updated->name);

        $updated = setKeyValue($doc, 'email', 'bob@example.com');
        $this->assertSame('bob@example.com', $updated->email);
    }

    public function testSetValueInObjectForced(): void
    {
        $doc = (object)['x' => 99];
        $updated = setKeyValue($doc, 'x', 123, false);
        $this->assertSame(123, $updated->x);
    }

    public function testSetNullValue(): void
    {
        $docArray = ['a' => 1];
        $updatedArray = setKeyValue($docArray, 'a', null);
        $this->assertNull($updatedArray['a']);

        $docObject = (object)['a' => 1];
        $updatedObject = setKeyValue($docObject, 'a', null);
        $this->assertNull($updatedObject->a);
    }

    public function testExceptionOnArrayExpectedButObjectGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type override');

        $doc = (object)['foo' => 'bar'];
        setKeyValue($doc, 'foo', 'baz', true);
    }

    public function testExceptionOnObjectExpectedButArrayGiven(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type override');

        $doc = ['foo' => 'bar'];
        setKeyValue($doc, 'foo', 'baz', false);
    }
}