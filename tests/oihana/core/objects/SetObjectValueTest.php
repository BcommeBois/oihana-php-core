<?php

declare(strict_types=1);

namespace oihana\core\objects;

use PHPUnit\Framework\TestCase;

final class SetObjectValueTest extends TestCase
{
    public function testSetExistingProperty(): void
    {
        $obj = (object)['name' => 'Alice'];
        $updated = setObjectValue($obj, 'name', 'Bob');
        $this->assertSame('Bob', $updated->name);
    }

    public function testSetNewProperty(): void
    {
        $obj = (object)['name' => 'Alice'];
        $updated = setObjectValue($obj, 'age', 30);
        $this->assertSame(30, $updated->age);
        $this->assertSame('Alice', $updated->name);
    }

    public function testSetNullValue(): void
    {
        $obj = (object)['x' => 1];
        $updated = setObjectValue($obj, 'x', null);
        $this->assertNull($updated->x);
    }

    public function testOriginalObjectIsModified(): void
    {
        $obj = (object)['a' => 1];
        setObjectValue($obj, 'b', 2);
        $this->assertSame(2, $obj->b); // Unlike array version, object is modified in-place
    }
}