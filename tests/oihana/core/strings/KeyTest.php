<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

final class KeyTest extends TestCase
{
    public function testKeyWithoutPrefixReturnsOriginalKey(): void
    {
        $this->assertSame('name', key('name'));
    }

    public function testKeyWithEmptyPrefixReturnsOriginalKey(): void
    {
        $this->assertSame('name', key('name', ''));
    }

    public function testKeyWithNullPrefixReturnsOriginalKey(): void
    {
        $this->assertSame('name', key('name', null));
    }

    public function testKeyWithPrefixAddsDefaultSeparator(): void
    {
        $this->assertSame('doc.name', key('name', 'doc'));
    }

    public function testKeyWithPrefixAndCustomSeparator(): void
    {
        $this->assertSame('doc::name', key('name', 'doc', '::'));
        $this->assertSame('doc->name', key('name', 'doc', '->'));
    }

    public function testKeyWithPrefixContainingDots(): void
    {
        $this->assertSame('prefix.with.dots.name', key('name', 'prefix.with.dots'));
    }
}