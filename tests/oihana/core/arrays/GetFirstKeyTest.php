<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

// composer test tests/oihana/core/arrays/GetFirstKeyTest.php

final class GetFirstKeyTest extends TestCase
{
    public function testReturnsFirstExistingKey(): void
    {
        $array = [
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ];

        $keys = ['baz', 'bar', 'foo'];
        $this->assertSame('baz', getFirstKey($array, $keys));
    }

    public function testReturnsLaterKeyIfEarlierDoesNotExist(): void
    {
        $array = [
            'bar' => 2,
            'baz' => 3,
        ];

        $keys = ['foo', 'bar', 'baz'];
        $this->assertSame('bar', getFirstKey($array, $keys));
    }

    public function testReturnsDefaultIfNoKeyExists(): void
    {
        $array = [
            'bar' => 2,
            'baz' => 3,
        ];

        $keys = ['foo', 'qux'];
        $this->assertSame('default', getFirstKey($array, $keys, 'default'));
    }

    public function testReturnsNullIfNoKeyExistsAndNoDefault(): void
    {
        $array = ['bar' => 2];
        $keys = ['foo', 'baz'];

        $this->assertNull(getFirstKey($array, $keys));
    }

    public function testWorksWithEmptyArray(): void
    {
        $array = [];
        $keys = ['foo', 'bar'];

        $this->assertSame('default', getFirstKey($array, $keys, 'default'));
    }

    public function testWorksWithEmptyKeys(): void
    {
        $array = ['foo' => 1];
        $keys = [];

        $this->assertSame('default', getFirstKey($array, $keys, 'default'));
    }

    public function testWorksWhenValueIsNull(): void
    {
        $array = ['foo' => null, 'bar' => 2];
        $keys = ['foo', 'bar'];

        // isset returns false for null, so it should skip 'foo' and return 'bar'
        $this->assertSame('bar', getFirstKey($array, $keys, 'default'));
    }
}
