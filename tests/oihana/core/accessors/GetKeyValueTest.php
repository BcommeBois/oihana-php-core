<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use InvalidArgumentException;
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
        $this->assertSame(42, getKeyValue($doc, 'x', isArray: true));
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
        $this->assertSame(99, getKeyValue($doc, 'x', isArray: false));
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

    public function testNestedKeyInArray(): void
    {
        $doc = ['user' => ['name' => 'Alice']];
        $this->assertSame('Alice', getKeyValue($doc, 'user.name'));
    }

    public function testNestedKeyInObject(): void
    {
        $doc = (object)['user' => (object)['name' => 'Bob']];
        $this->assertSame('Bob', getKeyValue($doc, 'user.name'));
    }

    public function testCustomSeparator(): void
    {
        $doc = ['x' => ['y' => ['z' => 100]]];
        $this->assertSame(100, getKeyValue($doc, 'x|y|z', separator: '|'));
    }

    public function testPartialPathMissing(): void
    {
        $doc = ['user' => ['name' => 'Charlie']];
        $this->assertNull(getKeyValue($doc, 'user.email'));
        $this->assertNull(getKeyValue($doc, 'profile.name'));
    }

    public function testPropertyNotAccessible(): void
    {
        $doc = new class {
            public string $visible = 'yes';
            private string $hidden = 'no';
        };

        $this->assertSame('yes', getKeyValue($doc, 'visible'));
        $this->assertNull(getKeyValue($doc, 'hidden')); // private, not accessible
    }

    public function testMagicGet(): void
    {
        $doc = new class {
            public function __get(string $name): ?string
            {
                return $name === 'foo' ? 'bar' : null;
            }
        };

        $this->assertSame('bar', getKeyValue($doc, 'foo'));
        $this->assertNull(getKeyValue($doc, 'notFound'));
    }

    public function testExceptionOnArrayExpectedButObjectGiven(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Type mismatch: expected array');

        $doc = (object)['foo' => 'bar'];
        getKeyValue($doc, 'foo', isArray: true);
    }

    public function testExceptionOnObjectExpectedButArrayGiven(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Type mismatch: expected object');

        $doc = ['foo' => 'bar'];
        getKeyValue($doc, 'foo', isArray: false);
    }

    public function testExceptionOnEmptyKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key cannot be empty.');

        $doc = ['a' => 1];
        getKeyValue($doc, '');
    }

    public function testExceptionOnEmptySeparator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Separator cannot be empty.');

        $doc = ['a' => 1];
        getKeyValue($doc, 'a', separator: '');
    }
}