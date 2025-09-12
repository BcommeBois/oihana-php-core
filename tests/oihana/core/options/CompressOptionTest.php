<?php

namespace oihana\core\options;

use PHPUnit\Framework\TestCase;

class CompressOptionTest extends TestCase
{
    public function testConstantsExist()
    {
        $this->assertTrue(defined(CompressOption::class . '::CLONE'));
        $this->assertTrue(defined(CompressOption::class . '::EXCLUDES'));
        $this->assertTrue(defined(CompressOption::class . '::DEPTH'));
        $this->assertTrue(defined(CompressOption::class . '::RECURSIVE'));
        $this->assertTrue(defined(CompressOption::class . '::REMOVE_KEYS'));
        $this->assertTrue(defined(CompressOption::class . '::THROWABLE'));
        $this->assertTrue(defined(CompressOption::class . '::CONDITIONS'));
    }

    public function testConstantsValues()
    {
        $this->assertSame('clone', CompressOption::CLONE);
        $this->assertSame('excludes', CompressOption::EXCLUDES);
        $this->assertSame('depth', CompressOption::DEPTH);
        $this->assertSame('recursive', CompressOption::RECURSIVE);
        $this->assertSame('removeKeys', CompressOption::REMOVE_KEYS);
        $this->assertSame('throwable', CompressOption::THROWABLE);
        $this->assertSame('conditions', CompressOption::CONDITIONS);
    }

    public function testCanUseConstantsInOptionsArray()
    {
        $options = [
            CompressOption::CLONE => true,
            CompressOption::EXCLUDES => ['id', 'name'],
            CompressOption::DEPTH => 2,
            CompressOption::RECURSIVE => true,
            CompressOption::REMOVE_KEYS => ['debug'],
            CompressOption::THROWABLE => false
        ];

        $this->assertArrayHasKey(CompressOption::CLONE, $options);
        $this->assertArrayHasKey(CompressOption::EXCLUDES, $options);
        $this->assertArrayHasKey(CompressOption::DEPTH, $options);
        $this->assertArrayHasKey(CompressOption::RECURSIVE, $options);
        $this->assertArrayHasKey(CompressOption::REMOVE_KEYS, $options);
        $this->assertArrayHasKey(CompressOption::THROWABLE, $options);

        $this->assertTrue($options[CompressOption::CLONE]);
        $this->assertSame(['id','name'], $options[CompressOption::EXCLUDES]);
        $this->assertSame(2, $options[CompressOption::DEPTH]);
        $this->assertTrue($options[CompressOption::RECURSIVE]);
        $this->assertSame(['debug'], $options[CompressOption::REMOVE_KEYS]);
        $this->assertFalse($options[CompressOption::THROWABLE]);
    }

    public function testNormalizeFillsDefaults()
    {
        $normalized = CompressOption::normalize([]);

        $this->assertFalse($normalized[CompressOption::CLONE]);
        $this->assertNull($normalized[CompressOption::EXCLUDES]);
        $this->assertNull($normalized[CompressOption::DEPTH]);
        $this->assertFalse($normalized[CompressOption::RECURSIVE]);
        $this->assertNull($normalized[CompressOption::REMOVE_KEYS]);
        $this->assertTrue($normalized[CompressOption::THROWABLE]);
        $this->assertIsArray($normalized[CompressOption::CONDITIONS]);
        $this->assertCount(1, $normalized[CompressOption::CONDITIONS]);
        $this->assertIsCallable($normalized[CompressOption::CONDITIONS][0]);
    }

    public function testNormalizePreservesUserValues()
    {
        $options = [
            CompressOption::CLONE => true,
            CompressOption::EXCLUDES => ['foo'],
            CompressOption::DEPTH => 3,
            CompressOption::RECURSIVE => true,
            CompressOption::REMOVE_KEYS => ['bar'],
            CompressOption::THROWABLE => false,
            CompressOption::CONDITIONS => fn($v) => $v === null,
        ];

        $normalized = CompressOption::normalize($options);

        $this->assertTrue($normalized[CompressOption::CLONE]);
        $this->assertSame(['foo'], $normalized[CompressOption::EXCLUDES]);
        $this->assertSame(3, $normalized[CompressOption::DEPTH]);
        $this->assertTrue($normalized[CompressOption::RECURSIVE]);
        $this->assertSame(['bar'], $normalized[CompressOption::REMOVE_KEYS]);
        $this->assertFalse($normalized[CompressOption::THROWABLE]);
        $this->assertCount(1, $normalized[CompressOption::CONDITIONS]);
        $this->assertIsCallable($normalized[CompressOption::CONDITIONS][0]);
    }

    public function testNormalizeWithNullOptions()
    {
        $normalized = CompressOption::normalize(null);

        $this->assertFalse($normalized[CompressOption::CLONE]);
        $this->assertNull($normalized[CompressOption::EXCLUDES]);
        $this->assertNull($normalized[CompressOption::DEPTH]);
        $this->assertFalse($normalized[CompressOption::RECURSIVE]);
        $this->assertNull($normalized[CompressOption::REMOVE_KEYS]);
        $this->assertTrue($normalized[CompressOption::THROWABLE]);
        $this->assertIsArray($normalized[CompressOption::CONDITIONS]);
        $this->assertIsCallable($normalized[CompressOption::CONDITIONS][0]);
    }

    public function testNormalizeConditionsCallableArray()
    {
        $cb1 = fn($v) => $v === null;
        $cb2 = fn($v) => $v === 0;

        $options = [
            CompressOption::CONDITIONS => [$cb1, $cb2]
        ];

        $normalized = CompressOption::normalize($options);
        $this->assertCount(2, $normalized[CompressOption::CONDITIONS]);
        $this->assertSame($cb1, $normalized[CompressOption::CONDITIONS][0]);
        $this->assertSame($cb2, $normalized[CompressOption::CONDITIONS][1]);
    }
}