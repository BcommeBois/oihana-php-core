<?php

namespace oihana\core\json;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use stdClass;

final class DeepJsonSerializeTest extends TestCase
{
    public function testReturnsScalarUnchanged(): void
    {
        $this->assertSame(42, deepJsonSerialize(42));
        $this->assertSame('hello', deepJsonSerialize('hello'));
        $this->assertSame(null, deepJsonSerialize(null));
        $this->assertSame(true, deepJsonSerialize(true));
    }

    public function testSerializesSimpleJsonSerializable(): void
    {
        $object = new class implements JsonSerializable
        {
            public function jsonSerialize(): array
            {
                return ['x' => 1];
            }
        };

        $result = deepJsonSerialize($object);
        $this->assertSame(['x' => 1], $result);
    }

    public function testRecursivelySerializesJsonSerializableInArray(): void
    {
        $nested = new class implements JsonSerializable
        {
            public function jsonSerialize(): array
            {
                return ['a' => 123];
            }
        };

        $data = [
            'outer' => [
                'inner' => $nested
            ],
        ];

        $result = deepJsonSerialize($data);
        $this->assertSame(['outer' => ['inner' => ['a' => 123]]], $result);
    }

    public function testRecursivelySerializesJsonSerializableInObject(): void
    {
        $nested = new class implements JsonSerializable
        {
            public function jsonSerialize(): array
            {
                return ['v' => 'ok'];
            }
        };

        $obj = new stdClass();
        $obj->child = $nested;

        $result = deepJsonSerialize($obj);
        $this->assertIsObject($result);
        $this->assertSame(['v' => 'ok'], $result->child);
    }

    public function testHandlesMixedArraysAndObjects(): void
    {
        $inner = new class implements JsonSerializable
        {
            public function jsonSerialize(): array
            {
                return ['deep' => 7];
            }
        };

        $object         = new stdClass();
        $object->nested = ['x' => $inner];

        $data = ['object' => $object];

        $result = deepJsonSerialize($data);

        $this->assertEquals(['object' => (object)['nested' => ['x' => ['deep' => 7]]]], $result);
    }

    public function testDoesNotAffectNonJsonSerializableObjects(): void
    {
        $obj = new stdClass();
        $obj->x = 1;

        $result = deepJsonSerialize($obj);

        // Même instance d’objet, propriétés inchangées
        $this->assertEquals($obj, $result);
        $this->assertSame(1, $result->x);
    }

    public function testDeeplyNestedSerialization(): void
    {
        $jsonObj = new class implements JsonSerializable
        {
            public function jsonSerialize(): array
            {
                return ['done' => true];
            }
        };

        $data = [
            'level1' => [
                'level2' => [
                    'level3' => $jsonObj
                ]
            ]
        ];

        $result = deepJsonSerialize($data);

        $this->assertSame(
            ['level1' => ['level2' => ['level3' => ['done' => true]]]],
            $result
        );
    }

    public function testSerializesWithinObjectProperties(): void
    {
        $user = new class implements JsonSerializable
        {
            public function jsonSerialize(): array
            {
                return ['id' => 99];
            }
        };

        $wrapper = new stdClass();
        $wrapper->user = $user;
        $wrapper->meta = ['a' => 'b'];

        $result = deepJsonSerialize($wrapper);

        $this->assertIsObject($result);
        $this->assertSame(['id' => 99], $result->user);
        $this->assertSame(['a' => 'b'], $result->meta);
    }

    public function testEmptyArrayAndEmptyObjectRemainUnchanged(): void
    {
        $this->assertSame([], deepJsonSerialize([]));

        $obj = new stdClass();
        $this->assertEquals($obj, deepJsonSerialize($obj));
    }
}