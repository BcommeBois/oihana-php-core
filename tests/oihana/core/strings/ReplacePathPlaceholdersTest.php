<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

final class ReplacePathPlaceholdersTest extends TestCase
{
    public function testEmptyPathReturnsEmptyString(): void
    {
        $this->assertSame('', replacePathPlaceholders(null));
        $this->assertSame('', replacePathPlaceholders(''));
    }

    public function testNoArgsReturnsOriginalPath(): void
    {
        $path = '/foo/{bar}/baz';
        $this->assertSame($path, replacePathPlaceholders($path));
    }

    public function testSimpleReplacement(): void
    {
        $path = '/observation/{observation}/workspace/{workspace}/places';
        $args = ['observation' => '15454', 'workspace' => '787878'];

        $expected = '/observation/15454/workspace/787878/places';
        $this->assertSame($expected, replacePathPlaceholders($path, $args));
    }

    public function testUndefinedPlaceholderFallback(): void
    {
        $path = '/foo/{missing}/bar';
        $args = ['other' => 'value'];

        // {missing} stays unchanged
        $expected = '/foo/{missing}/bar';
        $this->assertSame($expected, replacePathPlaceholders($path, $args));
    }

    public function testRegexPlaceholderIsReplaced(): void
    {
        $path = '/product/{product:[A-Za-z0-9_]+}/warehouse/{warehouse:[0-9]+}';
        $args = ['product' => 'ABC123', 'warehouse' => '42'];

        $expected = '/product/ABC123/warehouse/42';
        $this->assertSame($expected, replacePathPlaceholders($path, $args));
    }

    public function testPartialReplacement(): void
    {
        $path = '/user/{id}/post/{postId}';
        $args = ['id' => '10'];

        $expected = '/user/10/post/{postId}';
        $this->assertSame($expected, replacePathPlaceholders($path, $args));
    }

    public function testCustomPattern(): void
    {
        $path = '/foo/:bar/baz';
        $args = ['bar' => '123'];
        $pattern = '/:([a-zA-Z0-9_]+)/';

        $expected = '/foo/123/baz';
        $this->assertSame($expected, replacePathPlaceholders($path, $args, $pattern));
    }
}