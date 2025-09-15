<?php

declare(strict_types=1);

namespace oihana\core\objects;

use PHPUnit\Framework\TestCase;
use stdClass;

final class HasAnyPropertyTest extends TestCase
{
    public function testReturnsTrueIfPropertyExists()
    {
        $obj = (object)['id' => 123];
        $this->assertTrue( hasAnyProperty($obj, ['id']) );
    }

    public function testReturnsFalseIfNoPropertyExists()
    {
        $obj = (object)['id' => 123];
        $this->assertFalse(
            hasAnyProperty($obj, ['name', 'slogan'])
        );
    }

    public function testReturnsTrueIfAtLeastOnePropertyExists()
    {
        $obj = (object)['id' => 123];
        $this->assertTrue(
            hasAnyProperty($obj, ['name', 'id', 'slogan'])
        );
    }

    public function testNotNullOptionReturnsTrueIfNonNullPropertyExists()
    {
        $obj = (object)['id' => 123, 'slogan' => null];
        $this->assertTrue(
            hasAnyProperty($obj, ['slogan', 'id'], true) // 'id' is non-null
        );
    }

    public function testNotNullOptionReturnsFalseIfAllPropertiesAreNull()
    {
        $obj = (object)['id' => null, 'slogan' => null];
        $this->assertFalse(
            hasAnyProperty($obj, ['id', 'slogan'], true)
        );
    }

    public function testReturnsFalseIfObjectHasNoProperties()
    {
        $obj = new stdClass();
        $this->assertFalse(
            hasAnyProperty($obj, ['id', 'slogan'])
        );
    }
}