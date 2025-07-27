<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class HasKeyValueTest extends TestCase
{
    public function testHasKeyInFlatArray(): void
    {
        $doc = ['name' => 'Alice', 'age' => 30];
        $this->assertTrue(hasKeyValue($doc, 'name'));
        $this->assertFalse(hasKeyValue($doc, 'email'));
    }

    public function testHasKeyInFlatObject(): void
    {
        $doc = (object)['title' => 'Book', 'pages' => 100];
        $this->assertTrue(hasKeyValue($doc, 'title'));
        $this->assertFalse(hasKeyValue($doc, 'author'));
    }

    public function testHasNestedKeyArray(): void
    {
        $doc = ['user' => ['profile' => ['name' => 'Alice']]];
        $this->assertTrue(hasKeyValue($doc, 'user.profile.name'));
        $this->assertFalse(hasKeyValue($doc, 'user.profile.email'));
    }

    public function testHasNestedKeyObject(): void
    {
        $doc = (object)[
            'user' => (object)[
                'profile' => (object)[
                    'name' => 'Alice'
                ]
            ]
        ];
        $this->assertTrue(hasKeyValue($doc, 'user.profile.name'));
        $this->assertFalse(hasKeyValue($doc, 'user.profile.email'));
    }

    public function testCustomSeparator(): void
    {
        $doc = ['foo' => ['bar' => ['baz' => 123]]];
        $this->assertTrue(hasKeyValue($doc, 'foo/bar/baz', '/', true));
    }

    public function testEmptyKeyThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        hasKeyValue(['x' => 1], '');
    }

    public function testEmptySeparatorThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        hasKeyValue(['x' => 1], 'x', '');
    }

    public function testWrongForcedTypeArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        hasKeyValue((object)['x' => 1], 'x', '.', true);
    }

    public function testWrongForcedTypeObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        hasKeyValue(['x' => 1], 'x', '.', false);
    }

    public function testMagicGetIsNotDetected(): void
    {
        $doc = new class {
            public function __get(string $name): mixed
            {
                return 'foo';
            }
        };

        $this->assertFalse(hasKeyValue($doc, 'foo'));
    }
}