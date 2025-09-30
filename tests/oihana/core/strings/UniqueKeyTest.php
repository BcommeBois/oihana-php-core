<?php

declare(strict_types=1);

namespace oihana\core\strings;

use PHPUnit\Framework\TestCase;

final class UniqueKeyTest extends TestCase
{
    public function testBasicKey()
    {
        $key = uniqueKey(context: 'test', binds: ['a' => 1, 'b' => 2], prefix: 'prefix');
        $this->assertIsString($key);
        $this->assertEquals(64, strlen($key)); // SHA-256 length
    }

    public function testDeterminism()
    {
        $key1 = uniqueKey(context: 'test', binds: ['a' => 1, 'b' => 2]);
        $key2 = uniqueKey(context: 'test', binds: ['b' => 2, 'a' => 1]);
        $this->assertSame($key1, $key2);
    }

    public function testHashFalse()
    {
        $key = uniqueKey(context: 'test', binds: ['x' => 1, 'y' => 2], hash: false);
        $this->assertStringContainsString('ctx=test', $key);
        $this->assertStringContainsString('x=1', $key);
        $this->assertStringContainsString('y=2', $key);
    }

    public function testNullAndBoolBinds()
    {
        $key = uniqueKey(context: 'test', binds: ['n' => null, 't' => true, 'f' => false], hash: false);
        $this->assertStringContainsString('n=null', $key);
        $this->assertStringContainsString('t=true', $key);
        $this->assertStringContainsString('f=false', $key);
    }

    public function testArrayBind()
    {
        $binds = ['arr' => ['x' => 1, 'y' => 2]];
        $key = uniqueKey(context: 'arrTest', binds: $binds, hash: false);
        $this->assertStringContainsString('arr={"x":1,"y":2}', $key);
    }

    public function testObjectBind()
    {
        $obj = (object)['a' => 1, 'b' => 2];
        $key = uniqueKey(context: 'objTest', binds: ['obj' => $obj], hash: false);
        $this->assertStringContainsString('obj=', $key);
        $this->assertStringContainsString('a', $key);
        $this->assertStringContainsString('b', $key);
    }

    public function testClosureBind()
    {
        $closure = function(){ return 42; };
        $key = uniqueKey(context: 'closureTest', binds: ['c' => $closure], hash: false);
        $this->assertStringContainsString('c=closure', $key);
    }

    public function testUnicodeNormalization()
    {
        $str1 = "é";        // U+00E9
        $str2 = "e\u{301}"; // e + accent combiné

        $key1 = uniqueKey(context: 'unicode', binds: ['ch' => $str1], hash: false);
        $key2 = uniqueKey(context: 'unicode', binds: ['ch' => $str2], hash: false);

        $this->assertSame($key1, $key2);
    }
}
