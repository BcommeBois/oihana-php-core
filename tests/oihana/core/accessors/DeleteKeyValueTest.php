<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class DeleteKeyValueTest extends TestCase
{
    // ----------- Wildcards

    public function testDeleteAllArray(): void
    {
        $array = ['a' => 1, 'b' => 2];
        $result = deleteKeyValue($array, '*');
        $this->assertSame([], $result);
    }

    public function testDeleteAllObject(): void
    {
        $object = (object)['x' => 'hello', 'y' => 'world'];
        $result = deleteKeyValue($object, '*');
        $this->assertEquals((object)[], $result);
    }

    // ---------- Partial wildcard

    public function testDeleteWildcardInArray(): void
    {
        $array = ['user' => ['name' => 'Alice', 'email' => 'a@b.c']];
        $result = deleteKeyValue($array, 'user.*');
        $this->assertSame(['user' => []], $result);
    }

    public function testDeleteWildcardInObject(): void
    {
        $object = (object)[
            'user' => (object)['name' => 'Alice', 'email' => 'a@b.c']
        ];
        $result = deleteKeyValue($object, 'user.*');

        $this->assertEquals(
            (object)['user' => (object)[]],
            $result
        );
    }

    public function testDeleteWildcardOnNonArray(): void
    {
        $array = ['foo' => 'bar'];
        $result = deleteKeyValue($array, 'foo.*');
        // "foo" is scalar → nothing is removed
        $this->assertSame(['foo' => 'bar'], $result);
    }

    public function testDeleteWildcardOnMissingBranch(): void
    {
        $array = ['existing' => ['x' => 1]];
        $result = deleteKeyValue($array, 'missing.*');
        // nothing happens, branch does not exist
        $this->assertSame(['existing' => ['x' => 1]], $result);
    }

    // -------- Simple deletion

    public function testDeleteSimpleKeyInArray(): void
    {
        $array = ['name' => 'Alice', 'age' => 30, 'email' => 'a@b.c'];
        $result = deleteKeyValue($array, 'age');
        $this->assertSame(['name' => 'Alice', 'email' => 'a@b.c'], $result);
    }

    public function testDeleteSimpleKeyInObject(): void
    {
        $object = (object)['name' => 'Alice', 'age' => 30];
        $result = deleteKeyValue($object, 'age');
        $this->assertEquals((object)['name' => 'Alice'], $result);
    }

    public function testDeleteNonExistentSimpleKey(): void
    {
        $array = ['name' => 'Alice'];
        $result = deleteKeyValue($array, 'age');
        // No error, key simply doesn't exist
        $this->assertSame(['name' => 'Alice'], $result);
    }

    // -------- Dot notation

    public function testDeleteNestedKeyInArray(): void
    {
        $array = ['user' => ['name' => 'Alice', 'email' => 'a@b.c']];
        $result = deleteKeyValue($array, 'user.name');
        $this->assertSame(['user' => ['email' => 'a@b.c']], $result);
    }

    public function testDeleteNestedKeyInObject(): void
    {
        $object = (object)[
            'user' => (object)['name' => 'Alice', 'email' => 'a@b.c']
        ];
        $result = deleteKeyValue($object, 'user.email');
        $this->assertEquals(
            (object)['user' => (object)['name' => 'Alice']],
            $result
        );
    }

    public function testDeleteDeeplyNestedKey(): void
    {
        $array =
        [
            'app' =>
            [
                'config' =>
                [
                    'database' =>
                    [
                        'host' => 'localhost',
                        'port' => 3306
                    ]
                ]
            ]
        ];
        $result = deleteKeyValue($array, 'app.config.database.port');
        $expected =
        [
            'app' =>
            [
                'config' =>
                [
                    'database' =>
                    [
                        'host' => 'localhost'
                    ]
                ]
            ]
        ];
        $this->assertSame($expected, $result);
    }

    // -------- Multiple keys

    public function testDeleteMultipleSimpleKeys(): void
    {
        $array = ['name' => 'Alice', 'age' => 30, 'email' => 'a@b.c', 'phone' => '123'];
        $result = deleteKeyValue($array, ['age', 'phone']);
        $this->assertSame(['name' => 'Alice', 'email' => 'a@b.c'], $result);
    }

    public function testDeleteMultipleNestedKeys(): void
    {
        $array = [
            'user' => ['name' => 'Alice', 'email' => 'a@b.c', 'age' => 30],
            'meta' => ['created' => '2024-01-01', 'updated' => '2024-01-02']
        ];
        $result = deleteKeyValue($array, ['user.email', 'user.age', 'meta.updated']);
        $expected = [
            'user' => ['name' => 'Alice'],
            'meta' => ['created' => '2024-01-01']
        ];
        $this->assertSame($expected, $result);
    }

    public function testDeleteMultipleMixedKeys(): void
    {
        $array = [
            'name' => 'Alice',
            'age' => 30,
            'meta' => ['a' => 1, 'b' => 2],
            'config' => ['x' => 10, 'y' => 20]
        ];
        $result = deleteKeyValue($array, ['age', 'meta.*', 'config.x']);
        $expected = [
            'name' => 'Alice',
            'meta' => [],
            'config' => ['y' => 20]
        ];
        $this->assertSame($expected, $result);
    }

    public function testDeleteMultipleWithSomeNonExistent(): void
    {
        $array = ['name' => 'Alice', 'age' => 30];
        $result = deleteKeyValue($array, ['age', 'email', 'phone']);
        // Non-existent keys are simply ignored
        $this->assertSame(['name' => 'Alice'], $result);
    }

    public function testDeleteEmptyArrayOfKeys(): void
    {
        $array = ['name' => 'Alice', 'age' => 30];
        $result = deleteKeyValue($array, []);
        // Nothing deleted
        $this->assertSame(['name' => 'Alice', 'age' => 30], $result);
    }

    // --------- Custom separator

    public function testDeleteWithCustomSeparator(): void
    {
        $array = ['user' => ['profile' => ['name' => 'Alice', 'age' => 30]]];
        $result = deleteKeyValue($array, 'user/profile/age', separator: '/');
        $expected = ['user' => ['profile' => ['name' => 'Alice']]];
        $this->assertSame($expected, $result);
    }

    public function testDeleteWildcardWithCustomSeparator(): void
    {
        $array = ['meta' => ['a' => 1, 'b' => 2]];
        $result = deleteKeyValue($array, 'meta|*', separator: '|');
        $this->assertSame(['meta' => []], $result);
    }

    // --------- strict mode

    public function testStrictModeThrowsOnNonExistentKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Key 'email' does not exist.");

        $array = ['name' => 'Alice', 'age' => 30];
        deleteKeyValue($array, 'email', strict: true);
    }

    public function testStrictModeThrowsOnNonExistentNestedKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Key 'user.phone' does not exist.");

        $array = ['user' => ['name' => 'Alice']];
        deleteKeyValue($array, 'user.phone', strict: true);
    }

    public function testStrictModeSucceedsOnExistingKey(): void
    {
        $array = ['name' => 'Alice', 'age' => 30];
        $result = deleteKeyValue($array, 'age', strict: true);
        $this->assertSame(['name' => 'Alice'], $result);
    }

    public function testStrictModeWithMultipleKeysThrowsOnFirstMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Key 'email' does not exist.");

        $array = ['name' => 'Alice', 'age' => 30];
        deleteKeyValue($array, ['age', 'email', 'phone'], strict: true);
    }

    public function testStrictModeWithWildcardThrowsOnMissingParent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Key 'missing' does not exist.");

        $array = ['existing' => ['x' => 1]];
        deleteKeyValue($array, 'missing.*', strict: true);
    }

    // --------- Validation of the parameters

    public function testThrowsOnEmptyKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key cannot be empty.');

        $array = ['name' => 'Alice'];
        deleteKeyValue($array, '');
    }

    public function testThrowsOnEmptySeparator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Separator cannot be empty.');

        $array = ['name' => 'Alice'];
        deleteKeyValue($array, 'name', separator: '');
    }

    public function testThrowsOnArrayOfNonStringKeys(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All keys must be strings.');

        $array = ['name' => 'Alice'];
        deleteKeyValue($array, ['age', 123, 'email']);
    }

    public function testThrowsOnTypeMismatchForceArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Type mismatch: expected array, got stdClass.');

        $object = (object)['name' => 'Alice'];
        deleteKeyValue($object, 'name', isArray: true);
    }

    public function testThrowsOnTypeMismatchForceObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Type mismatch: expected object, got array.');

        $array = ['name' => 'Alice'];
        deleteKeyValue($array, 'name', isArray: false);
    }

    // ========================================================================
    // Tests de détection automatique du type
    // ========================================================================

    public function testAutoDetectArrayType(): void
    {
        $array = ['name' => 'Alice', 'age' => 30];
        $result = deleteKeyValue($array, 'age');
        $this->assertIsArray($result);
        $this->assertSame(['name' => 'Alice'], $result);
    }

    public function testAutoDetectObjectType(): void
    {
        $object = (object)['name' => 'Alice', 'age' => 30];
        $result = deleteKeyValue($object, 'age');
        $this->assertIsObject($result);
        $this->assertEquals((object)['name' => 'Alice'], $result);
    }

    // --------- Limit cases

    public function testDeleteFromEmptyArray(): void
    {
        $array = [];
        $result = deleteKeyValue($array, 'anything');
        $this->assertSame([], $result);
    }

    public function testDeleteFromEmptyObject(): void
    {
        $object = new stdClass();
        $result = deleteKeyValue($object, 'anything');
        $this->assertEquals(new stdClass(), $result);
    }

    public function testDeletePreservesOtherNestedStructures(): void
    {
        $array = [
            'user' => ['name' => 'Alice', 'email' => 'a@b.c'],
            'admin' => ['name' => 'Bob', 'email' => 'b@c.d']
        ];
        $result = deleteKeyValue($array, 'user.email');
        $expected =
        [
            'user' => ['name' => 'Alice'],
            'admin' => ['name' => 'Bob', 'email' => 'b@c.d']
        ];
        $this->assertSame($expected, $result);
    }

    public function testDeleteWithMixedArrayObject(): void
    {
        $mixed = [
            'data' => (object)['name' => 'Alice', 'age' => 30]
        ];
        $result = deleteKeyValue($mixed, 'data.age');

        // Vérifier que 'data' est toujours un objet
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertIsObject($result['data']);
        $this->assertObjectHasProperty('name', $result['data']);
        $this->assertObjectNotHasProperty('age', $result['data']);
        $this->assertEquals('Alice', $result['data']->name);
    }

    // --------- Performance test

    public function testDeleteMultipleKeysInLargeStructure(): void
    {
        $large =
        [
            'user' =>
            [
                'profile' =>
                [
                    'name'  => 'Alice',
                    'email' => 'a@b.c',
                    'age'   => 30,
                    'phone' => '123'
                ],
                'settings' =>
                [
                    'theme'         => 'dark',
                    'language'      => 'en',
                    'notifications' => true
                ]
            ],
            'meta' =>
            [
                'created' => '2024-01-01',
                'updated' => '2024-01-02',
                'version' => 1
            ]
        ];

        $result = deleteKeyValue( $large ,
        [
            'user.profile.phone',
            'user.settings.notifications',
            'meta.updated'
        ]);

        $expected =
        [
            'user' =>
            [
                'profile' =>
                [
                    'name'  => 'Alice',
                    'email' => 'a@b.c',
                    'age'   => 30
                ],
                'settings' =>
                [
                    'theme'    => 'dark',
                    'language' => 'en'
                ]
            ],
            'meta' =>
            [
                'created' => '2024-01-01',
                'version' => 1
            ]
        ];

        $this->assertSame($expected, $result);
    }
}