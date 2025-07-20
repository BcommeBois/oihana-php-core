<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

// composer test tests/oihana/core/arrays/GetTest.php

class GetTest extends TestCase
{
    private array $data;

    protected function setUp(): void
    {
        $this->data = [
            'user' => [
                'name' => 'Alice',
                'address' => [
                    'city' => 'Paris',
                    'geo' => [
                        'lat' => 48.8566,
                        'lng' => 2.3522,
                    ],
                ],
            ],
            'items' => [10, 20, 30],
            'empty' => [],
        ];
    }

    public function testGetSimpleKey()
    {
        $this->assertSame('Alice', get($this->data, 'user.name'));
    }

    public function testGetNestedKey()
    {
        $this->assertSame('Paris', get($this->data, 'user.address.city'));
        $this->assertSame(48.8566, get($this->data, 'user.address.geo.lat'));
    }

    public function testGetNonExistentKeyReturnsDefault()
    {
        $this->assertSame('unknown', get($this->data, 'user.phone', 'unknown'));
        $this->assertNull(get($this->data, 'foo.bar'));
    }

    public function testGetWithCallableDefault()
    {
        $default = fn() => 'generated_default';
        $this->assertSame('generated_default', get($this->data, 'non.existent.key', $default));
    }

    public function testGetWithNullKeyReturnsWholeArray()
    {
        $this->assertSame($this->data, get($this->data, null));
    }

    public function testGetKeyAtTopLevel()
    {
        $this->assertSame([10, 20, 30], get($this->data, 'items'));
    }

    public function testGetEmptyArray()
    {
        $this->assertSame([], get($this->data, 'empty'));
    }

    public function testGetKeyWithCustomSeparator()
    {
        $data = ['a' => ['b' => ['c' => 123]]];
        $this->assertSame(123, get($data, 'a|b|c', null, '|'));
    }
}
