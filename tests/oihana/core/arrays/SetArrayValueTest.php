<?php

declare(strict_types=1);

namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

final class SetArrayValueTest extends TestCase
{
    public function testSetExistingKey(): void
    {
        $array = ['name' => 'Alice'];
        $updated = setArrayValue($array, 'name', 'Bob');
        $this->assertSame('Bob', $updated['name']);
    }

    public function testSetNewKey(): void
    {
        $array = ['name' => 'Alice'];
        $updated = setArrayValue($array, 'age', 30);
        $this->assertSame(30, $updated['age']);
        $this->assertSame('Alice', $updated['name']);
    }

    public function testSetNullValue(): void
    {
        $array = ['x' => 1];
        $updated = setArrayValue($array, 'x', null);
        $this->assertNull($updated['x']);
    }

    public function testOriginalArrayIsUnchanged(): void
    {
        $array = ['a' => 1];
        $updated = setArrayValue($array, 'b', 2);
        $this->assertArrayNotHasKey('b', $array);
        $this->assertSame(2, $updated['b']);
    }
}