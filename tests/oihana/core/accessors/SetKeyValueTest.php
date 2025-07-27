<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

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
        $updated = setKeyValue($doc, 'x', 100, '.', true); // <- corrigé : $isArray est le 5e argument
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
        $updated = setKeyValue($doc, 'x', 123, '.', false); // <- corrigé
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
        $this->expectException( InvalidArgumentException::class);
        $this->expectExceptionMessage('Type mismatch: expected array');

        $doc = (object)['foo' => 'bar'] ;

        setKeyValue($doc, 'foo', 'baz', '.', true); // <- corrigé
    }

    public function testExceptionOnObjectExpectedButArrayGiven(): void
    {
        $this->expectException( InvalidArgumentException::class);
        $this->expectExceptionMessage('Type mismatch: expected object');

        $doc = ['foo' => 'bar'];
        setKeyValue($doc, 'foo', 'baz', '.', false); // <- corrigé
    }

    public function testNestedSetInArray(): void
    {
        $doc = [];
        $updated = setKeyValue($doc, 'user.profile.name', 'Alice');
        $this->assertSame('Alice', $updated['user']['profile']['name']);
    }

    public function testNestedSetInObject(): void
    {
        $doc = new stdClass();
        $updated = setKeyValue($doc, 'user.profile.name', 'Bob', '.', false);
        $this->assertSame('Bob', $updated->user->profile->name);
    }

    public function testOverwriteIntermediateScalarInArray(): void
    {
        $doc = ['user' => 'not_array'];
        $updated = setKeyValue($doc, 'user.profile.name', 'Alice');

        $this->assertIsArray($updated['user']);
        $this->assertSame('Alice', $updated['user']['profile']['name']);
    }

    public function testOverwriteIntermediateScalarInObject(): void
    {
        $doc = (object)['user' => 'not_object'];
        $updated = setKeyValue($doc, 'user.profile.name', 'Bob', '.', false);

        $this->assertIsObject($updated->user);
        $this->assertSame('Bob', $updated->user->profile->name);
    }
}