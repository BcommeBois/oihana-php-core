<?php

namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

// composer test tests/oihana/core/arrays/RemoveKeysTest.php

class RemoveKeysTest extends TestCase
{
    public function testRemoveSingleKey()
    {
        $array = ['name' => 'Alice', 'email' => 'alice@example.com', 'age' => 30];
        $result = removeKeys($array, ['email']);

        $this->assertSame(['name' => 'Alice', 'age' => 30], $result);
    }

    public function testRemoveMultipleKeys()
    {
        $array = ['name' => 'Alice', 'email' => 'alice@example.com', 'age' => 30];
        $result = removeKeys($array, ['name', 'age']);

        $this->assertSame(['email' => 'alice@example.com'], $result);
    }

    public function testRemoveKeyThatDoesNotExist()
    {
        $array = ['x' => 1];
        $result = removeKeys($array, ['y']);

        $this->assertSame(['x' => 1], $result);
    }

    public function testEmptyKeysArrayReturnsSameArray()
    {
        $array = ['foo' => 'bar'];
        $result = removeKeys($array, []);

        $this->assertSame(['foo' => 'bar'], $result);
    }

    public function testCloneTrueDoesNotModifyOriginal()
    {
        $original = ['a' => 1, 'b' => 2, 'c' => 3];
        $copy = removeKeys($original, ['a'], true);

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $original);
        $this->assertSame(['b' => 2, 'c' => 3], $copy);
    }

    public function testCloneFalseModifiesCopyOnly()
    {
        $array = ['a' => 1, 'b' => 2];
        $result = removeKeys($array, ['a'], false);

        $this->assertSame(['b'=>2], $array);
        $this->assertSame(['b'=>2], $result);
    }

    public function testRemoveKeysOnEmptyArray()
    {
        $array = [];
        $result = removeKeys($array , ['a', 'b']);

        $this->assertSame([], $result);
    }
}