<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

// composer test tests/oihana/core/arrays/GetFirstValueTest.php

final class GetFirstValueTest extends TestCase
{
    public function testReturnsValueOfFirstExistingKey(): void
    {
        $array = [
            'foo' => 1,
            'bar' => 2,
            'baz' => 3,
        ];

        $keys = ['baz', 'bar', 'foo'];
        $this->assertSame(3, getFirstValue($array, $keys));
    }

    public function testReturnsLaterKeyValueIfEarlierDoesNotExist(): void
    {
        $array = [
            'bar' => 2,
            'baz' => 3,
        ];

        $keys = ['foo', 'bar', 'baz'];
        $this->assertSame(2, getFirstValue($array, $keys));
    }

    public function testReturnsDefaultIfNoKeyExists(): void
    {
        $array = [
            'bar' => 2,
            'baz' => 3,
        ];

        $keys = ['foo', 'qux'];
        $this->assertSame('default', getFirstValue($array, $keys, 'default'));
    }

    public function testReturnsNullIfNoKeyExistsAndNoDefault(): void
    {
        $array = ['bar' => 2];
        $keys = ['foo', 'baz'];

        $this->assertNull(getFirstValue($array, $keys));
    }

    public function testWorksWithEmptyArray(): void
    {
        $array = [];
        $keys = ['foo', 'bar'];

        $this->assertSame('default', getFirstValue($array, $keys, 'default'));
    }

    public function testWorksWithEmptyKeys(): void
    {
        $array = ['foo' => 1];
        $keys = [];

        $this->assertSame('default', getFirstValue($array, $keys, 'default'));
    }

    public function testReturnsValueEvenIfValueIsNull(): void
    {
        $array = ['foo' => null, 'bar' => 2];
        $keys = ['foo', 'bar'];

        // Unlike getFirstKey(), getFirstValue() should return null if the key exists
        $this->assertNull(getFirstValue($array, $keys, 'default'));
    }
}
