<?php

declare(strict_types=1);

namespace oihana\core\objects;

use PHPUnit\Framework\TestCase;
use stdClass;

final class HasAllPropertiesTest extends TestCase
{
    public function testReturnsTrueIfAllPropertiesExist()
    {
        $obj = (object)['id' => 123, 'slogan' => 'Hello'];
        $this->assertTrue(
            hasAllProperties($obj, ['id', 'slogan'])
        );
    }

    public function testReturnsFalseIfOnePropertyIsMissing()
    {
        $obj = (object)['id' => 123];
        $this->assertFalse(
            hasAllProperties($obj, ['id', 'slogan'])
        );
    }

    public function testReturnsFalseIfAllPropertiesAreMissing()
    {
        $obj = (object)['id' => 123];
        $this->assertFalse(
            hasAllProperties($obj, ['name', 'description'])
        );
    }

    public function testNotNullOptionReturnsTrueIfAllPropertiesExistAndNotNull()
    {
        $obj = (object)['id' => 123, 'slogan' => 'Hello'];
        $this->assertTrue(
            hasAllProperties($obj, ['id', 'slogan'], true)
        );
    }

    public function testNotNullOptionReturnsFalseIfOnePropertyIsNull()
    {
        $obj = (object)['id' => 123, 'slogan' => null];
        $this->assertFalse(
            hasAllProperties($obj, ['id', 'slogan'], true)
        );
    }

    public function testReturnsTrueForEmptyPropertiesList()
    {
        $obj = (object)['id' => 123];
        $this->assertTrue(
            hasAllProperties($obj, [])
        );
    }

    public function testReturnsFalseIfObjectHasNoProperties()
    {
        $obj = new stdClass();
        $this->assertFalse(
            hasAllProperties($obj, ['id'])
        );
    }
}