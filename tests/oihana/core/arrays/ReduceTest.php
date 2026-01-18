<?php

namespace oihana\core\arrays ;

use oihana\core\options\CompressOption;
use PHPUnit\Framework\TestCase;

final class ReduceTest extends TestCase
{
    public function testReduceTrueRemovesNulls()
    {
        $data = ['a' => 1, 'b' => null, 'c' => 2];
        $result = reduce($data, true);

        $this->assertSame(['a' => 1, 'c' => 2], $result);
    }

    public function testReduceWithCompressOptions()
    {
        $data = ['a' => '', 'b' => null, 'c' => 'ok'];
        $options =
        [
            CompressOption::CONDITIONS =>
            [
                fn($v) => $v === null,
                fn($v) => $v === ''
            ]
        ];

        $result = reduce($data, $options);

        $this->assertSame(['c' => 'ok'], $result);
    }

    public function testReduceWithCallable()
    {
        $data = ['name' => 'Alice', 'age' => 0, 'city' => 'Paris'];
        $callable = fn($k, $v) => is_string($v) && $v !== '';

        $result = reduce($data, $callable);

        $this->assertSame(['name' => 'Alice', 'city' => 'Paris'], $result);
    }

    public function testReduceWithEmptyArray()
    {
        $result = reduce([], true);
        $this->assertSame([], $result);

        $result = reduce([], fn($v,$k) => $v !== null);
        $this->assertSame([], $result);
    }

    public function testReduceWithInvalidTypeReturnsOriginal()
    {
        $data = ['a' => 1, 'b' => 2];

        $result = reduce($data, 123);      // int → invalid
        $this->assertSame($data, $result);

        $result = reduce($data, 'invalid'); // string → invalid
        $this->assertSame($data, $result);
    }

    public function testReducePreservesKeys()
    {
        $data = ['x' => null, 'y' => 'keep', 'z' => 0];

        $result = reduce($data, true);

        $this->assertArrayHasKey('y', $result);
        $this->assertArrayHasKey('z', $result);
        $this->assertArrayNotHasKey('x', $result);
    }
}
