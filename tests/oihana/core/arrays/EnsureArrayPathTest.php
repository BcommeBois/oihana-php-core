<?php

declare(strict_types=1);

namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

final class EnsureArrayPathTest extends TestCase
{
    public function testCreatesEmptyArrayWhenKeyMissing(): void
    {
        $data = [];
        $ref =& ensureArrayPath($data, 'foo');

        $this->assertIsArray($ref);
        $this->assertSame([], $ref);
        $this->assertArrayHasKey('foo', $data);
        $this->assertSame([], $data['foo']);
    }

    public function testReturnsExistingArray(): void
    {
        $data = ['foo' => ['bar' => 1]];
        $ref =& ensureArrayPath($data, 'foo');

        $this->assertSame(['bar' => 1], $ref);
        $this->assertSame(['bar' => 1], $data['foo']);
    }

    public function testOverwritesNonArrayValue(): void
    {
        $data = ['foo' => 42];
        $ref =& ensureArrayPath($data, 'foo');

        $this->assertSame([], $ref);
        $this->assertIsArray($data['foo']);
    }

    public function testReferenceAssignmentModifiesOriginal(): void
    {
        $data = [];
        $ref =& ensureArrayPath($data, 'settings');
        $ref['enabled'] = true;

        $this->assertSame(['enabled' => true], $data['settings']);
    }
}