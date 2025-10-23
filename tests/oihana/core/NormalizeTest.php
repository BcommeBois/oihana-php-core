<?php

namespace oihana\core ;

use oihana\core\arrays\CleanFlag;
use PHPUnit\Framework\TestCase;

final class NormalizeTest extends TestCase
{
    public function testEmptyStringsAndNulls(): void
    {
        $data = ['', '   ' , ' foo ', null];
        $expected = [' foo '];
        $this->assertSame($expected, normalize($data));
    }

    public function testAllEmptyBecomesNull(): void
    {
        $data = ['', null, '   '];
        $this->assertNull(normalize($data));
    }

    public function testSingleStringTrimmed(): void
    {
        $this->assertNull(normalize('   '));
        $this->assertSame('bar', normalize('bar'));
    }

    public function testAssociativeArray(): void
    {
        $data = [
            'name'  => 'Alice',
            'email' => '  ',
            'age'   => null
        ];
        $expected = ['name' => 'Alice'];
        $this->assertSame($expected, normalize($data));
    }

    public function testFalsyValues(): void
    {
        $data = [
            'zero'    => 0,
            'empty'   => '',
            'nullVal' => null,
            'ok'      => 'valid'
        ];
        $expected = ['ok' => 'valid'];
        $flags = CleanFlag::FALSY | CleanFlag::RECURSIVE;
        $this->assertSame($expected, normalize($data, $flags));
    }

    public function testNestedArraysRecursive(): void
    {
        $data = [
            'users' => [
                ['name' => '', 'email' => 'bob@example.com'],
                ['name' => 'Alice', 'email' => '']
            ]
        ];
        $expected = [
            'users' => [
                ['email' => 'bob@example.com'],
                ['name' => 'Alice']
            ]
        ];
        $flags = CleanFlag::RECURSIVE | CleanFlag::EMPTY;
        $this->assertSame($expected, normalize($data, $flags));
    }

    public function testScalarFalsyWithFlag(): void
    {
        $this->assertNull(normalize('', CleanFlag::FALSY));
        $this->assertNull(normalize(0, CleanFlag::FALSY));
        $this->assertNull(normalize(false, CleanFlag::FALSY));
        $this->assertSame('ok', normalize('ok', CleanFlag::FALSY));
    }

    public function testArrayWithReturnNull(): void
    {
        $data = ['', [], null];
        $this->assertNull(normalize($data, CleanFlag::DEFAULT | CleanFlag::RETURN_NULL));
    }

    public function testArrayWithoutReturnNull(): void
    {
        $data = ['', [], null];
        $this->assertSame([], normalize($data, CleanFlag::DEFAULT));
    }

    public function testArrayEmpty(): void
    {
        $data = [];
        $this->assertNull(normalize($data, CleanFlag::DEFAULT | CleanFlag::RETURN_NULL));
    }
}