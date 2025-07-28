<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use PHPUnit\Framework\TestCase;

final class DeleteKeyValueTest extends TestCase
{
    public function testDeleteAllArray(): void
    {
        $array = ['a' => 1, 'b' => 2];
        $result = deleteKeyValue($array, '*');
        $this->assertSame([], $result);
    }

    public function testDeleteAllObject(): void
    {
        $object = (object)['x' => 'hello', 'y' => 'world'];
        $result = deleteKeyValue($object, '*');
        $this->assertEquals((object)[], $result);
    }

    public function testDeleteWildcardInArray(): void
    {
        $array = ['user' => ['name' => 'Alice', 'email' => 'a@b.c']];
        $result = deleteKeyValue($array, 'user.*');
        $this->assertSame(['user' => []], $result);
    }

    public function testDeleteWildcardInObject(): void
    {
        $object = (object)[
            'user' => (object)['name' => 'Alice', 'email' => 'a@b.c']
        ];
        $result = deleteKeyValue($object, 'user.*');

        $this->assertEquals(
            (object)['user' => (object)[]],
            $result
        );
    }

    public function testDeleteWildcardOnNonArray(): void
    {
        $array = ['foo' => 'bar'];
        $result = deleteKeyValue($array, 'foo.*');
        // "foo" is scalar → nothing is removed
        $this->assertSame(['foo' => 'bar'], $result);
    }

    public function testDeleteWildcardOnMissingBranch(): void
    {
        $array = ['existing' => ['x' => 1]];
        $result = deleteKeyValue($array, 'missing.*');
        // nothing happens, branch does not exist
        $this->assertSame(['existing' => ['x' => 1]], $result);
    }
}