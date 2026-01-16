<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use PHPUnit\Framework\TestCase;

final class EnsureKeyValueTest extends TestCase
{
    public function testEnsureSimpleKeyInArray(): void
    {
        $doc = [];

        $doc = ensureKeyValue($doc, 'name');

        $this->assertArrayHasKey('name', $doc);
        $this->assertNull($doc['name']);
    }

    public function testEnsureSimpleKeyInObject(): void
    {
        $doc = (object)[];

        $doc = ensureKeyValue($doc, 'name');

        $this->assertObjectHasProperty('name', $doc);
        $this->assertNull($doc->name);
    }

    public function testEnsureNestedKeyInArray(): void
    {
        $doc = [];

        $doc = ensureKeyValue($doc, 'user.profile.email');

        $this->assertTrue(hasKeyValue($doc, 'user.profile.email'));
        $this->assertNull(getKeyValue($doc, 'user.profile.email'));
    }

    public function testEnsureNestedKeyInObject(): void
    {
        $doc = (object)[];

        $doc = ensureKeyValue($doc, 'meta.version.major');

        $this->assertTrue(hasKeyValue($doc, 'meta.version.major'));
        $this->assertNull(getKeyValue($doc, 'meta.version.major'));
    }

    public function testEnsureDoesNotOverrideExistingValue(): void
    {
        $doc = [
            'config' => [
                'debug' => [
                    'enabled' => true
                ]
            ]
        ];

        $doc = ensureKeyValue($doc, 'config.debug.enabled');

        $this->assertSame(true, getKeyValue($doc, 'config.debug.enabled'));
    }

    public function testEnsureWithCustomDefaultValue(): void
    {
        $doc = [];

        $doc = ensureKeyValue($doc, 'config.cache.ttl', 3600);

        $this->assertSame(3600, getKeyValue($doc, 'config.cache.ttl'));
    }

    public function testEnsureMultipleKeys(): void
    {
        $doc = [];

        $doc = ensureKeyValue(
            $doc,
            [
                'meta.author',
                'meta.version.major',
                'meta.version.minor',
            ]
        );

        $this->assertTrue(hasKeyValue($doc, 'meta.author'));
        $this->assertTrue(hasKeyValue($doc, 'meta.version.major'));
        $this->assertTrue(hasKeyValue($doc, 'meta.version.minor'));

        $this->assertNull(getKeyValue($doc, 'meta.author'));
        $this->assertNull(getKeyValue($doc, 'meta.version.major'));
        $this->assertNull(getKeyValue($doc, 'meta.version.minor'));
    }

    public function testEnsureKeepsExistingStructureIntact(): void
    {
        $doc = [
            'user' => [
                'name' => 'Alice'
            ]
        ];

        $doc = ensureKeyValue($doc, 'user.profile.email');

        $this->assertSame('Alice', getKeyValue($doc, 'user.name'));
        $this->assertNull(getKeyValue($doc, 'user.profile.email'));
    }

    public function testEnsureWithForcedArrayMode(): void
    {
        $doc = [];

        $doc = ensureKeyValue($doc, 'a.b.c', null, '.', true);

        $this->assertIsArray($doc['a']);
        $this->assertIsArray($doc['a']['b']);
        $this->assertArrayHasKey('c', $doc['a']['b']);
    }

    public function testEnsureWithForcedObjectMode(): void
    {
        $doc = (object)[];

        $doc = ensureKeyValue($doc, 'a.b.c', null, '.', false);

        $this->assertIsObject($doc->a);
        $this->assertIsObject($doc->a->b);
        $this->assertObjectHasProperty('c', $doc->a->b);
    }

    public function testEnforceInitializesNonInitializedTypedProperty(): void
    {
        $doc = new class { public string $name; };

        $doc = ensureKeyValue($doc, 'name', 'default', '.', null, true);

        $this->assertSame('default', getKeyValue($doc, 'name'));
    }

    public function testEnforceDoesNotOverrideExistingTypedProperty(): void
    {
        $doc = new class {
            public string $name = 'Alice';
        };

        $doc = ensureKeyValue($doc, 'name', 'default', '.', null, true);

        $this->assertSame('Alice', $doc->name);
    }

    public function testEnforceWithMultipleKeysIncludingObjectsAndArrays(): void
    {
        $doc = (object)[];

        $keys = [
            'user.name',
            'user.profile.age',
            'config.cache.ttl'
        ];

        $doc = ensureKeyValue($doc, $keys, 'unknown', '.', null, true);

        $this->assertSame('unknown', getKeyValue($doc, 'user.name'));
        $this->assertSame('unknown', getKeyValue($doc, 'user.profile.age'));
        $this->assertSame('unknown', getKeyValue($doc, 'config.cache.ttl'));
    }

    public function testEnforceKeepsExistingValues(): void
    {
        $doc = [
            'user' => [
                'name' => 'Bob'
            ]
        ];

        $doc = ensureKeyValue($doc, ['user.name'], 'Alice', '.', null, true);

        $this->assertSame('Bob', getKeyValue($doc, 'user.name'));
    }

    public function testEnsureWithSpecificDefaultsInAssociativeArray(): void
    {
        $doc = [];

        $doc = ensureKeyValue($doc, [
            'user.role' => 'admin',  // Défaut spécifique
            'user.active'            // Défaut global
        ], true); // true est le défaut global

        $this->assertSame('admin', getKeyValue($doc, 'user.role'));
        $this->assertTrue(getKeyValue($doc, 'user.active'));
    }

    public function testEnsureWithClosureAsDefault(): void
    {
        $doc = [];

        $doc = ensureKeyValue($doc, 'generated_id', fn() => uniqid('test_'));

        $this->assertStringStartsWith('test_', getKeyValue($doc, 'generated_id'));
    }

    public function testClosureIsOnlyCalledWhenNeeded(): void
    {
        $doc = ['existing' => 'value'];
        $called = false;

        ensureKeyValue($doc, 'existing', function() use (&$called) {
            $called = true;
            return 'new_value';
        });

        $this->assertFalse($called, 'Closure should not be executed if key exists');
        $this->assertSame('value', $doc['existing']);
    }

    public function testClosureIsCalledWhenEnforcingUninitializedProperty(): void
    {
        $doc = new class { public string $name; };
        $called = false;

        ensureKeyValue($doc, 'name', function() use (&$called) {
            $called = true;
            return 'Computed Name';
        }, '.', null, true);

        $this->assertTrue($called);
        $this->assertSame('Computed Name', $doc->name);
    }

    public function testEnforceInitializesNestedTypedProperty(): void
    {
        $leaf   = new class { public string $title; };
        $middle = new class { public object $leaf; };
        $root   = new class { public object $middle; };

        $root->middle = $middle;
        $middle->leaf = $leaf;

        ensureKeyValue($root, 'middle.leaf.title', 'Hero', '.', null, true);

        $this->assertSame('Hero', $root->middle->leaf->title);
    }
}